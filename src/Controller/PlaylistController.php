<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Playlist;
use App\Entity\Usuario;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

final class PlaylistController extends AbstractController
{
    #[Route('/playlist', name: 'app_playlist')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/PlaylistController.php',
        ]);
    }

    #[Route('/playlist/new', name: 'playlist_new')]
    public function create(EntityManagerInterface $em): Response
    {
        $usuario = $em->getRepository(Usuario::class)->find(1); // Obtén un usuario existente

        $playlist = new Playlist();
        $playlist->setUsuarioPropietario($usuario);
        $playlist->setNombre('Mi Playlist');
        $playlist->setVisibilidad('Publica');
        $playlist->setLikes(10);


        $em->persist($playlist);
        $em->flush();

        return new Response('Playlist creada con ID ' . $playlist->getId());
    }
    #[Route('/playlist/show/{id}', name: 'playlist_show', methods: ['GET'])]
    public function show(EntityManagerInterface $em, int $id): Response
    {
        $playlist = $em->getRepository(Playlist::class)->find($id); // Obtén un usuario existente

        $songData = [];
        $musicDirectory = '/songs/';
        $imageDirectory = '/images/';
        $nombre = $playlist->getNombre();

        foreach ($playlist->getPlaylistCancions() as $song) {
            $imageDirectory = 'images/';
            if(!file_exists($imageDirectory . $song->getCancion()->getImagen())){
                $imagen = "cancionDefault.jpg";
                $imageDirectory = '/images/';
            }else{
                $imageDirectory = '/images/';
                $imagen = $song->getCancion()->getImagen();
            }
            $songData[] = [
                'name' => $song->getCancion()->getTitulo(),
                'image' => $imageDirectory . $imagen,
                'file' => $musicDirectory . $song->getCancion()->getArchivo(),
            ];
        }

        return $this->render('music/playlist.html.twig', [
            'songs' => $songData,
            'nombre' => $nombre,
        ]);
    }

    

}
