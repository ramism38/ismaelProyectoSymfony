<?php

namespace App\Controller;

use App\Entity\Cancion;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Playlist;
use App\Entity\PlaylistCancion;
use App\Entity\Usuario;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

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

    #[Route('/playlist/new', name: 'create_playlist', methods: ['POST'])]
    public function createPlaylist(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'No autenticado'], 403);
        }

        $usuarioObject = $entityManager->getRepository(Usuario::class)->findOneBy(["email"=>$user->getUserIdentifier()]);

        $data = json_decode($request->getContent(), true);
        if (!isset($data['name']) || empty(trim($data['name']))) {
            return new JsonResponse(['error' => 'Nombre de playlist requerido'], 400);
        }

        $playlist = new Playlist();
        $playlist->setNombre($data['name']);
        $playlist->setUsuarioPropietario($usuarioObject);
        $playlist->setVisibilidad("Privada");
        $playlist->setLikes(0);

        $entityManager->persist($playlist);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Playlist creada exitosamente',
                                'user' => $user]);
    }


    #[Route('/playlist/show/{id}', name: 'playlist_show', methods: ['GET'])]
    public function show(EntityManagerInterface $em, int $id): Response
    {
        $playlist = $em->getRepository(Playlist::class)->find($id); // Obtén un usuario existente

        $songDataPlaylist = [];
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
            $songDataPlaylist[] = [
                'name' => $song->getCancion()->getTitulo(),
                'image' => $imageDirectory . $imagen,
                'file' => $musicDirectory . $song->getCancion()->getArchivo(),
            ];
        }

        // Recuperar canciones desde la base de datos
        $songs = $em->getRepository(Cancion::class)->findAll();

        // Crear estructura para la vista
        $songData = [];
        $imagen = "cancionDefault.jpg";


        foreach ($songs as $song) {
            if (!file_exists($imageDirectory . $song->getImagen())) {
                $imagen = "cancionDefault.jpg";
            } else {
                $imagen = $song->getImagen();
            }
            $songData[] = [
                'name' => $song->getTitulo(),
                'id' => $song->getId(),
                'image' => $imageDirectory . $imagen, // Puedes personalizar esto
                'file' => $musicDirectory . $song->getArchivo(), // Ruta del archivo
            ];
        }

        return $this->render('music/playlist.html.twig', [
            'songsPlaylist' => $songDataPlaylist,
            'nombre' => $nombre,
            'propietario' => $playlist->getUsuarioPropietario()->getId(),
            'playlistId' => $playlist->getId(),
            'songs' => $songData,
        ]);
    }

    #[Route('playlist/add-songs/{id}', name: 'playlist_add_songs', methods: ['POST'])]
    public function addSongsToPlaylist(int $id, Request $request, EntityManagerInterface $entityManager): JsonResponse
{
    // Obtener la playlist
    $playlist = $entityManager->getRepository(Playlist::class)->find($id);
    
    if (!$playlist) {
        return new JsonResponse(['success' => false, 'message' => 'Playlist no encontrada'], 404);
    }

    // Verificar si el usuario es el dueño de la playlist
    $user = $this->getUser();
    if ($playlist->getUsuarioPropietario() !== $user) {
        return new JsonResponse(['success' => false, 'message' => 'No tienes permiso para modificar esta playlist'], 403);
    }

    // Decodificar los datos del request
    $data = json_decode($request->getContent(), true);
    if (!isset($data['songIds']) || !is_array($data['songIds'])) {
        return new JsonResponse(['success' => false, 'message' => 'Datos inválidos'], 400);
    }

    // Buscar las canciones y agregarlas a la playlist
    $cancionRepository = $entityManager->getRepository(Cancion::class);
    
    foreach ($data['songIds'] as $songId) {
        $cancion = $cancionRepository->find($songId);
        
        if ($cancion) {
            // Crear una nueva relación PlaylistCancion
            $playlistCancion = new PlaylistCancion();
            $playlistCancion->setPlaylist($playlist);
            $playlistCancion->setCancion($cancion);

            // Guardar en la base de datos
            $entityManager->persist($playlistCancion);
        }
    }

    // Guardar todos los cambios en la base de datos
    $entityManager->flush();

    return new JsonResponse(['success' => true, 'message' => 'Canciones añadidas correctamente']);
}


    

}
