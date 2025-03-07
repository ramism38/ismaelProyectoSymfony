<?php

namespace App\Controller\Admin;

use App\Controller\LogController;
use App\Entity\Estilo;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class EstiloCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Estilo::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $this->forward(LogController::class . '::logAction', ['action' => 'Estilo - CRUD']);
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nombre'),
            TextField::new('descripcion'),
            
        ];
    }
}
