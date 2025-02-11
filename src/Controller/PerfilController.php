<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Perfil;
use App\Entity\Estilo;
use App\Entity\Usuario;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

final class PerfilController extends AbstractController
{
    #[Route('/perfil', name: 'app_perfil')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/PerfilController.php',
        ]);
    }

    #[Route('/perfil/new', name: 'perfil_new')]
    public function create(EntityManagerInterface $em): Response
    {
        $estilo = $em->getRepository(Estilo::class)->find(1); // Obtén un estilo existente
        $usuario = $em->getRepository(Usuario::class)->find(1); // Obtén un estilo existente

        $perfil = new Perfil();

        $perfil->setUsuario($usuario);
        $perfil->setFoto('foto.png');
        $perfil->setDescripcion('Descripción del perfil');
        $perfil->addEstilosMusicalesPreferido($estilo);

        $em->persist($perfil);
        $em->flush();

        return new Response('Perfil creado con ID ' . $perfil->getId());
    }
}
