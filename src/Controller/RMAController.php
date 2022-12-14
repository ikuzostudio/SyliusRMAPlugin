<?php

namespace Ikuzo\SyliusRMAPlugin\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Ikuzo\SyliusRMAPlugin\Entity\RMARequest;
use Ikuzo\SyliusRMAPlugin\Form\RMAFormType;
use Sylius\Component\Mailer\Sender\SenderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Ikuzo\SyliusRMAPlugin\Model\RMAChannelInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Order\Model\OrderInterface;
use Sylius\Component\Order\Repository\OrderRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

class RMAController extends AbstractController
{
    public function __construct(private array $reasons)
    {
    }

    public function rmaContactAction(Request $request, String $number, ManagerRegistry $em, ChannelContextInterface $channelContext, SenderInterface $emailSender, TranslatorInterface $translator, OrderRepositoryInterface $orderRepository, CustomerContextInterface $customerContext): Response
    {
        $channel = $channelContext->getChannel();
        if (!$channel instanceof RMAChannelInterface || !$channel->isRMAEnabled()) {
            return new RedirectResponse($this->generateUrl('sylius_shop_homepage'));
        }

        /** @var OrderInterface|null $order */
        $order = $orderRepository->findOneByNumber($number);
        if (!$order instanceof OrderInterface) {
            throw new NotFoundHttpException('Order not found');
        }

        if (!$order->getCustomer() == $customerContext->getCustomer()) {
            return new RedirectResponse($this->generateUrl('sylius_shop_homepage'));
        }

        if ($order->getState() != 'fulfilled') {
            return new RedirectResponse($this->generateUrl('sylius_shop_account_order_index'));
        }

        $data = [];

        $form = $this->createForm(RMAFormType::class, $data, [
            'order' => $order,
            'reasons' => $this->reasons
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $data = $form->getData();

                $rmaRequest = new RMARequest();

                $rmaRequest->setChannel($channel);
                $rmaRequest->setOrder($order);
                $rmaRequest->setReason($data['reason']);
                $rmaRequest->setComment($data['comment']);
                $rmaRequest->setState('requested');
                
                foreach ($data['items'] as $item) {
                    $rmaRequest->addVariant($item->getVariant());
                }

                $em->getManager()->persist($rmaRequest);
                $em->getManager()->flush();

                $emailSender->send('rma_request', [$channel->getRMAEmail()], [
                    'order' => $order,
                    'data' => $data,
                    'customer' => $order->getCustomer(),
                ]);
                $this->addFlash('success', $translator->trans('ikuzo_rma.form.request_sent'));
            } else {
                $this->addFlash('error', $translator->trans('ikuzo_rma.form.invalid'));
            }
            return new RedirectResponse($this->generateUrl('sylius_shop_account_order_index'));
        }

        return $this->render('@IkuzoSyliusRMAPlugin/rma.html.twig', [
            'form' => $form->createView(),
            'order' => $order
        ]);
    }
}