<?php

namespace App\Controller\Admin;

use App\Controller\LogController;
use App\Entity\PlaylistCancion;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class PlaylistCancionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PlaylistCancion::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $this->forward(LogController::class . '::logAction', ['action' => 'PlaylistCancion - CRUD']);
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('cancion', 'Cancion')->setFormTypeOption('by_reference', true),
            AssociationField::new('playlist', 'Playlist')->hideOnForm()->setFormTypeOption('by_reference', true),
            //IntegerField::new('reproducciones'),
        ];
    }
}
