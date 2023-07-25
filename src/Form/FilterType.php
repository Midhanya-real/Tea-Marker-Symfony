<?php

namespace App\Form;

use App\Entity\Brand;
use App\Entity\Category;
use App\Entity\Country;
use App\Entity\Type;
use App\Services\ProductFilterService\Entity\Filter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'multiple' => true,
                'expanded' => true,
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('brands', EntityType::class, [
                'class' => Brand::class,
                'multiple' => true,
                'expanded' => true,
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('types', EntityType::class, [
                'class' => Type::class,
                'multiple' => true,
                'expanded' => true,
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('countries', EntityType::class, [
                'class' => Country::class,
                'multiple' => true,
                'expanded' => true,
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('minPrice', NumberType::class, [
                'label' => 'minimum price',
                'scale' => 2,
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ]

            ])
            ->add('maxPrice', NumberType::class, [
                'label' => 'maximum price',
                'scale' => 2,
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('minWeight', NumberType::class, [
                'label' => 'minimum weight',
                'scale' => 2,
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('maxWeight', NumberType::class, [
                'label' => 'maximum weight',
                'scale' => 2,
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Filter::class,
            'default_min_price' => 0,
            'default_max_price' => 10000,
            'default_min_weight' => 0,
            'default_max_weight' => 10000
        ]);
    }
}
