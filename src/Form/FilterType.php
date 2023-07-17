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
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
            ])
            ->add('brands', EntityType::class, [
                'class' => Brand::class,
                'multiple' => true,
                'expanded' => true,
                'required' => false
            ])
            ->add('types', EntityType::class, [
                'class' => Type::class,
                'multiple' => true,
                'expanded' => true,
                'required' => false
            ])
            ->add('countries', EntityType::class, [
                'class' => Country::class,
                'multiple' => true,
                'expanded' => true,
                'required' => false
            ])
            ->add('minPrice', NumberType::class, [
                'label' => 'minimum price',
                'required' => false

            ])
            ->add('maxPrice', NumberType::class, [
                'label' => 'maximum price',
                'required' => false
            ])
            ->add('minWeight', NumberType::class, [
                'label' => 'minimum weight',
                'required' => false
            ])
            ->add('maxWeight', NumberType::class, [
                'label' => 'maximum weight',
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Filter::class
        ]);
    }
}
