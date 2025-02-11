<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class PlaylistCancion {
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private int $id;


    #[ORM\ManyToOne(targetEntity: Cancion::class, inversedBy: 'playlistCanciones')]
    private ?Cancion $cancion = null;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $reproducciones = 0;

    #[ORM\ManyToOne(inversedBy: 'playlistCancions')]
    private ?Playlist $playlist = null;

    public function getId(): int {
        return $this->id;
    }

    public function getCancion(): ?Cancion {
        return $this->cancion;
    }

    public function setCancion(?Cancion $cancion): self {
        $this->cancion = $cancion;
        return $this;
    }

    public function getReproducciones(): int {
        return $this->reproducciones;
    }

    public function setReproducciones(int $reproducciones): self {
        $this->reproducciones = $reproducciones;
        return $this;
    }

    public function getPlaylist(): ?Playlist
    {
        return $this->playlist;
    }

    public function setPlaylist(?Playlist $playlist): static
    {
        $this->playlist = $playlist;

        return $this;
    }

    public function __toString()
    {
        return $this->getCancion()->getTitulo();
    }
}
