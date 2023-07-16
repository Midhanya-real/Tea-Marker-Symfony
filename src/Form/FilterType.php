<?php

namespace App\Form;

use App\Services\ProductFilterService\Entity\Filter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('categories')
            ->add('brands')
            ->add('types')
            ->add('countries')
            ->add('prices')
            ->add('weights');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Filter::class
        ]);
    }
}
