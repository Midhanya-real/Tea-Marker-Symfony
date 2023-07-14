<?php

namespace App\Controller\Admin;

use App\config\Enums\OrderStatus;
use App\Entity\Payment;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\UuidType;

class PaymentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Payment::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('yookassa_id', 'payment service id')->setFormType(UuidType::class),
            AssociationField::new('order_id'),
            AssociationField::new('user_id'),
            NumberField::new('price')->setNumDecimals(2),
            ChoiceField::new('status', 'status')
                ->hideOnIndex()
                ->setFormType(EnumType::class)
                ->setFormTypeOption('class', OrderStatus::class)
                ->setChoices(['status' => OrderStatus::cases()]),
        ];
    }

}
