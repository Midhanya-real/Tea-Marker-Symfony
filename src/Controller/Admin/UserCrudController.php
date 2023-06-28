<?php

namespace App\Controller\Admin;

use App\config\Enums\UserRole;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            yield EmailField::new('email'),
            yield TextField::new('password')->setFormType(PasswordType::class),
            yield ChoiceField::new('roles')
                ->setChoices(UserRole::cases())
                ->allowMultipleChoices(),
        ];
    }

}
