<?php

namespace App\Form;

use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options,
    ): void {
        $builder
            ->add('customerName', TextType::class, ['label' => 'Jméno zákazníka'])
            ->add('total', IntegerType::class, ['label' => 'Cena objednávky', 'attr' => ['min' => 0]]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,

            'empty_data' => function (FormInterface $form): Order {
                return new Order(
                    $form->get('customerName')->getData(),
                    $form->get('total')->getData(),
                );
            },
        ]);
    }
}