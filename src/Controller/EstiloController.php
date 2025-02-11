<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Estilo;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

final class EstiloController extends AbstractController
{
    #[Route('/estilo', name: 'app_estilo')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/EstiloController.php',
        ]);
    }

    #[Route('/estilo/new', name: 'estilo_new')]
    public function create(EntityManagerInterface $em): Response
    {
        $estilo = new Estilo();
        $estilo->setNombre('Rock');
        $estilo->setDescripcion('Estilo musical de Rock');

        $em->persist($estilo);
        $em->flush();

        return new Response('Estilo creado con ID ' . $estilo->getId());
    }
}
