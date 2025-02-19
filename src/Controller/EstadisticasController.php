<?php

namespace App\Controller;

use App\Repository\CancionRepository;
use App\Repository\PlaylistCancionRepository;
use App\Repository\UsuarioRepository;
use App\Repository\PlaylistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EstadisticasController extends AbstractController
{
    #[Route('/manager/estadisticas', name: 'estadisticas_dashboard')]
    public function index()
    {
        return $this->render('estadisticas/index.html.twig');
    }

    #[Route('/manager/estadisticas/playlist-likes', name: 'estadisticas_playlist_likes')]
    public function playlistLikes(PlaylistRepository $playlistRepository): JsonResponse
    {
        $playlists = $playlistRepository->findAll();
        $data = [];

        foreach ($playlists as $playlist) {
            $data[] = [
                'nombre' => $playlist->getNombre(),
                'likes' => $playlist->getLikes(),
            ];
        }

        return new JsonResponse($data);
    }

    #[Route('/manager/estadisticas/playlist-reproducciones', name: 'estadisticas_playlist_reproducciones')]
    public function playlistReproducciones(EntityManagerInterface $em): JsonResponse
    {
        $query = $em->createQuery("
            SELECT p.nombre, SUM(pc.reproducciones) as total_reproducciones
            FROM App\Entity\Playlist p
            JOIN App\Entity\PlaylistCancion pc WITH pc.playlist = p.id
            GROUP BY p.id
            ORDER BY total_reproducciones DESC
        ");

        $result = $query->getResult();

        return new JsonResponse($result);
    }

    #[Route('/manager/estadisticas/usuarios-edad', name: 'estadisticas_usuarios_edad')]
    public function usuariosEdad(UsuarioRepository $usuarioRepository): JsonResponse
    {
        $usuarios = $usuarioRepository->findAll();
        $edades = [];

        foreach ($usuarios as $usuario) {
            $fechaNacimiento = new \DateTime($usuario->getFechaNacimiento());
            $edad = $fechaNacimiento->diff(new \DateTime())->y;
            $edades[] = $edad;
        }

        return new JsonResponse($edades);
    }

    #[Route('/manager/estadisticas/canciones-mas-reproducidas', name: 'estadisticas_canciones_mas_reproducidas')]
    public function cancionesMasReproducidas(PlaylistCancionRepository $playlistCancionRepository): JsonResponse
    {  
        $canciones = $playlistCancionRepository->findBy([], ['reproducciones' => 'DESC'], 10);
        $data = [];

        foreach ($canciones as $cancion) {
            $data[] = [
                'reproduciones' => $cancion->getReproducciones(),
                'titulo' => $cancion->getCancion()->getTitulo(),
            ];
        }

        return new JsonResponse($data);
    }
}
