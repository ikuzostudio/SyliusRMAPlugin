<?php

namespace Ikuzo\SyliusRMAPlugin\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Ikuzo\SyliusRMAPlugin\Form\RMAFormType;
use Sylius\Component\Core\Model\Order;
use Sylius\Component\Mailer\Sender\SenderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Ikuzo\SyliusRMAPlugin\Model\RMAChannelInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class RMAController extends AbstractController
{
    public function rmaContactAction(Request $request, ManagerRegistry $em, ChannelContextInterface $channelContext): Response
    {
        $channel = $channelContext->getChannel();

        if (!$channel instanceof RMAChannelInterface || !$channel->isRMAEnabled()) {
            return new RedirectResponse($this->generateUrl('sylius_shop_homepage'));
        }

        
        $user = $this->getUser();
        $orders = $em->getRepository(Order::class)->findBy(['customer' => $user]);

        return $this->render('@IkuzoSyliusRMAPlugin/rma-contact.html.twig', [
            'orders' => $orders,
        ]);
    }

    public function rmaFormAction(Request $request, ManagerRegistry $em, SenderInterface $emailSender, ChannelContextInterface $channelContext, TranslatorInterface $translator)
    {
        $data = [];

        $channel = $channelContext->getChannel();

        if (!$channel instanceof RMAChannelInterface || !$channel->isRMAEnabled()) {
            return new RedirectResponse($this->generateUrl('sylius_shop_homepage'));
        }

        $order = $request->query->get('id');
        if (!$order) {
            return new JsonResponse(['error' => 'Order not found'], Response::HTTP_NOT_FOUND);
        }
        /** @var Order|null $order */
        $order = $em->getRepository(Order::class)->find($order);

        if (!$order) {
            return new JsonResponse(['error' => 'Order not found'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(RMAFormType::class, $data, [
            'order' => $order,
            'method' => 'POST',
            'action' => $this->generateUrl($request->get('_route'), ['id' => $order->getId()]),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $data = $form->getData();

                $emailSender->send('rma_request', [$channel->getRMAEmail()], [
                    'order' => $order,
                    'data' => $data,
                    'customer' => $order->getCustomer(),
                ]);
                $this->addFlash('success', $translator->trans('ikuzo_rma.form.request_sent'));

                return new RedirectResponse($this->generateUrl('ikuzo_rma_contact_page'));
            }

            $this->addFlash('error', $translator->trans('ikuzo_rma.form.invalid'));
            return new RedirectResponse($this->generateUrl('ikuzo_rma_contact_page'));
        }

        $formView = $form->createView();

        $html = $this->renderView('@IkuzoSyliusRMAPlugin/_partials/_form.html.twig', [
            'form' => $formView,
        ]);

        return new JsonResponse(['html' => $html]);
    }
}