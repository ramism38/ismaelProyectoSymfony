<?php

namespace App\Controller\Admin;

use App\Entity\UsuarioListenPlaylist;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class UsuarioListenPlaylistCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return UsuarioListenPlaylist::class;
    }

    public function configureFieldsSetUsuarioListenCancion(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('playlist', 'Playlist')->setFormTypeOption('by_reference', false),
            AssociationField::new('usuario', 'Usuario')->setFormTypeOption('by_reference', false), 
        ];
    }
}
