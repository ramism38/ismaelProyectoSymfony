<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Usuario;
use App\Entity\Playlist;
use App\Entity\UsuarioListenPlaylist;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

final class UsuarioListenPlaylistController extends AbstractController
{
    #[Route('/usuario/listen/playlist', name: 'app_usuario_listen_playlist')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UsuarioListenPlaylistController.php',
        ]);
    }

    #[Route('/usuario_listen_playlist/new', name: 'usuario_listen_playlist_new')]
    public function create(EntityManagerInterface $em): Response
    {
        $usuario = $em->getRepository(Usuario::class)->find(1); // Obtén un perfil existente
        $playlist = $em->getRepository(Playlist::class)->find(1); // Obtén un perfil existente

        $ulp = new UsuarioListenPlaylist();
        $ulp->setPlaylist($playlist);
        $ulp->setUsuario($usuario);

        $em->persist($ulp);
        $em->flush();

        return new Response('UsuarioListenPlaylist creado con ID ' . $ulp->getId());
    }
}
