<?php

namespace App\Controller\Admin;

use App\Controller\LogController;
use App\Entity\Usuario;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class UsuarioCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Usuario::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $this->forward(LogController::class . '::logAction', ['action' => 'Usuario - CRUD']);
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('perfil', 'Perfil')->setFormTypeOption('by_reference', false), 
            TextField::new('email'),
            TextField::new('password'),
            TextField::new('nombre'),
            TextField::new('fecha_nacimiento'),
        ];
    }
}
