<?php
// src/Controller/MusicController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Cancion;
use App\Entity\Playlist;
use App\Entity\Usuario;

class MusicController extends AbstractController
{
    #[Route('/music', name: 'music_list')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Recuperar canciones desde la base de datos
        $songs = $entityManager->getRepository(Cancion::class)->findAll();

        // Crear estructura para la vista
        $songData = [];
        $musicDirectory = 'songs/';
        $imageDirectory = 'images/';
        $imagen = "cancionDefault.jpg";


        foreach ($songs as $song) {
            if (!file_exists($imageDirectory . $song->getImagen())) {
                $imagen = "cancionDefault.jpg";
            } else {
                $imagen = $song->getImagen();
            }
            $songData[] = [
                'name' => $song->getTitulo(),
                'image' => $imageDirectory . $imagen, // Puedes personalizar esto
                'file' => $musicDirectory . $song->getArchivo(), // Ruta del archivo
            ];
        }

        // Recuperar playlists desde la base de datos según el usuario autenticado
        $user = $this->getUser();

        $playlistsData = [];
        if ($user) {
            // Si el usuario está autenticado, mostrar solo sus playlists
            $usuarioObject = $entityManager->getRepository(Usuario::class)->findOneBy(["email"=>$user->getUserIdentifier()]);
            $playlists = $entityManager->getRepository(Playlist::class)->findBy(['usuarioPropietario' => $usuarioObject->getId()]);
             // Crear estructura para la vista
            foreach ($playlists as $playlist) {
                $playlistsData[] = [
                    'name' => $playlist->getNombre(),
                    'image' => $imageDirectory . "playlistDefault.jpg",
                    'id' => $playlist->getId(),
                ];
        }
        }/*else {
            // Si no hay usuario, opcionalmente podríamos mostrar todas las playlists o ninguna
            $playlists = $entityManager->getRepository(Playlist::class)->findAll();
        }*/

        $playlistsG = $entityManager->getRepository(Playlist::class)->findAll();
        $playlistsGlobalData = [];

        foreach ($playlistsG as $playlist) {
            $playlistsGlobalData[] = [
                'name' => $playlist->getNombre(),
                'image' => $imageDirectory . "playlistDefault.jpg",
                'id' => $playlist->getId(),
            ];
        }


        return $this->render('music/index.html.twig', [
            'songs' => $songData,
            'playlists' => $playlistsData,
            'playlistGlobal' => $playlistsGlobalData,
        ]);
    }
}
