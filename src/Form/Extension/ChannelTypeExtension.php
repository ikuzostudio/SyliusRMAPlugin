<?php

namespace Ikuzo\SyliusRMAPlugin\Form\Extension;

use Sylius\Bundle\ChannelBundle\Form\Type\ChannelType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

final class ChannelTypeExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rmaEnabled', CheckboxType::class, [
                'required' => false,
                'label' => 'ikuzo_rma.form.active',
            ])
            ->add('rmaEmail', EmailType::class, [
                'required' => false,
                'label' => 'ikuzo_rma.form.email',
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\Email([
                        'groups' => ['sylius'],
                    ]),
                ],
            ])
        ;
    }

    public static function getExtendedTypes(): iterable
    {
        return [ChannelType::class];
    }
}