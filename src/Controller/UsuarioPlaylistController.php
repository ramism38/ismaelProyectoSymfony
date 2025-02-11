<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\UsuarioPlaylist;
use App\Entity\Usuario;
use App\Entity\Playlist;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

final class UsuarioPlaylistController extends AbstractController
{
    #[Route('/usuario/playlist', name: 'app_usuario_playlist')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UsuarioPlaylistController.php',
        ]);
    }

    #[Route('/usuario_playlist/new', name: 'usuario_playlist_new')]
    public function create(EntityManagerInterface $em): Response
    {
        $usuario = $em->getRepository(Usuario::class)->find(1); // Obtén un usuario existente
        $playlist = $em->getRepository(Playlist::class)->find(1); // Obtén una playlist existente

        if (!$usuario || !$playlist) {
            return new Response('Usuario o Playlist no encontrados.');
        }

        $usuarioPlaylist = new UsuarioPlaylist();
        $usuarioPlaylist->setUsuario($usuario);
        $usuarioPlaylist->setPlaylist($playlist);

        $em->persist($usuarioPlaylist);
        $em->flush();

        return new Response('Usuario asociado a Playlist con éxito.');
    }
}
