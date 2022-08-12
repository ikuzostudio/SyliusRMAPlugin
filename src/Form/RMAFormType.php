<?php

namespace Ikuzo\SyliusRMAPlugin\Form;

use Sylius\Component\Core\Model\OrderItem;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RMAFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('items', EntityType::class, [
                'multiple' => true,
                'expanded' => true,
                'choices' => $options['order']->getItems(),
                'class' => OrderItem::class,
                'choice_label' => function (OrderItem $item) {
                    return $item->getVariant()->getProduct()->getName();
                },
                'choice_value' => function (OrderItem $item) {
                    return $item->getId();
                },
                'required' => false,
                'label' => 'ikuzo_rma.form.products',
            ])
            ->add('reason', ChoiceType::class, [
                'choices' => array_flip($options['reasons']),
                'required' => true,
                'label' => 'ikuzo_rma.form.reason',
            ])
            ->add('comment', TextareaType::class, [
                'required' => false,
                'label' => 'ikuzo_rma.form.comment',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
            'reasons' => []
        ]);
        $resolver->setAllowedTypes('reasons', 'array');
        $resolver->setRequired('order');
    }
}