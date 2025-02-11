<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\PlaylistCancion;
use App\Entity\Cancion;
use App\Entity\Playlist;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

final class PlaylistCancionController extends AbstractController
{
    #[Route('/playlist/cancion', name: 'app_playlist_cancion')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/PlaylistCancionController.php',
        ]);
    }

    #[Route('/playlist_cancion/new', name: 'playlist_cancion_new')]
    public function create(EntityManagerInterface $em): Response
    {
        $playlist = $em->getRepository(Playlist::class)->find(1); // Obtén una playlist existente
        $cancion = $em->getRepository(Cancion::class)->find(1); // Obtén una canción existente


        $playlistCancion = new PlaylistCancion();
        $playlistCancion->setPlaylist($playlist);
        $playlistCancion->setCancion($cancion);
        $playlistCancion->setReproducciones(1000);

        $em->persist($playlistCancion);
        $em->flush();

        return new Response('Canción asociada a Playlist con éxito.');
    }
}
