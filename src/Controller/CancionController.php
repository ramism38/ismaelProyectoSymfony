<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Cancion;
use App\Entity\Estilo;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


final class CancionController extends AbstractController
{
    /*#[Route('/cancion', name: 'app_cancion')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/CancionController.php',
        ]);
    }*/

    #[Route('/cancion/new', name: 'cancion_new')]
    public function create(EntityManagerInterface $em): Response
    {
        $estilo = $em->getRepository(Estilo::class)->find(1); // Obtén un estilo existente

        $cancion = new Cancion();
        $cancion->setGenero($estilo);
        $cancion->setTitulo('Canción de ejemplo');
        $cancion->setDuracion(180);
        $cancion->setAlbum('Álbum de ejemplo');
        $cancion->setAutor('Autor de ejemplo');
        $cancion->setLikes(100);

        $em->persist($cancion);
        $em->flush();

        return new Response('Canción creada con ID ' . $cancion->getId());
    }

    #[Route('/cancion/{songName}/play', name: 'play_music', methods: ['GET'])]
    public function playMusic(string $songName): Response
    {
        $musicDirectory = $this->getParameter('kernel.project_dir') . '/songs/';
        $filePath = $musicDirectory . $songName;
        if (!file_exists($filePath)) {
            return new Response('Archivo no encontrado', 404);
        }
        return new BinaryFileResponse($filePath);
    }
}
