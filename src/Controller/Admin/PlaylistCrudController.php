<?php

namespace App\Controller\Admin;

use App\Entity\Playlist;
use App\Controller\Admin\PlaylistCancionCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class PlaylistCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Playlist::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nombre'),
            TextField::new('visibilidad'),
            IntegerField::new('likes'),
            AssociationField::new('usuarioPropietario', 'Usuario')->setFormTypeOption('by_reference', true),
            CollectionField::new('playlistCancions', 'Canciones')->useEntryCrudForm(PlaylistCancionCrudController::class)
        ];
    }
}
