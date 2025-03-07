<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AccesoDenegadoController extends AbstractController
{
    #[Route('/acceso-denegado', name: 'acceso_denegado')]
    public function accesoDenegado()
    {
        $this->forward(LogController::class . '::logAction', ['action' => 'Se deniega el acceso']);
        return $this->render('acceso_denegado/index.html.twig');
    }
}
