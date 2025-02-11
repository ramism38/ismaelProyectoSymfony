<?php

namespace App\Controller\Admin;

use App\Entity\Perfil;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PerfilCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Perfil::class;
    }

    public function configureFieldsSetPerfil(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('foto'),
            TextField::new('descripcion'),
        ];
    }
}
