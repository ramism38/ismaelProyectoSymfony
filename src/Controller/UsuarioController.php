<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Usuario;
use App\Entity\Perfil;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

final class UsuarioController extends AbstractController
{
    #[Route('/usuario', name: 'app_usuario')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UsuarioController.php',
        ]);
    }

    #[Route('/usuario/new', name: 'usuario_new')]
    public function create(EntityManagerInterface $em): Response
    {
        $perfil = $em->getRepository(Perfil::class)->find(1); // Obtén un perfil existente

        $usuario = new Usuario();
        $usuario->setPerfil($perfil);
        $usuario->setEmail('example@example.com');
        $usuario->setPassword('password123');
        $usuario->setNombre('Juan Perez');
        $usuario->setFechaNacimiento("1990-01-01");

        $em->persist($usuario);
        $em->flush();

        return new Response('Usuario creado con ID ' . $usuario->getId());
    }
}
