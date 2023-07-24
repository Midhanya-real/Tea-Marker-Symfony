<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('country', CountryType::class, [
                'label' => 'Country'
            ])
            ->add('city', TextType::class, [
                'label' => 'City'
            ])
            ->add('house', NumberType::class, [
                'label' => 'House'
            ])
            ->add('apartment', NumberType::class, [
                'label' => 'Apartment'
            ])
            ->add('post_code', NumberType::class, [
                'label' => 'Postcode',
                'attr' => [
                    'min' => 6
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
