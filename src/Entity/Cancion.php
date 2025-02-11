<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
class Cancion {
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string')]
    private string $titulo;

    #[ORM\Column(type: 'integer')]
    private int $duracion;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $album = null;

    #[ORM\Column(type: 'string')]
    private string $autor;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $likes = 0;

    #[ORM\ManyToOne(targetEntity: Estilo::class, inversedBy: 'canciones')]
    private ?Estilo $genero = null;

    #[ORM\ManyToMany(targetEntity: Usuario::class, mappedBy: 'cancionesFavoritas')]
    private Collection $usuarios;

    #[ORM\OneToMany(mappedBy: 'cancion', targetEntity: PlaylistCancion::class)]
    private Collection $playlistCanciones;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $archivo = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imagen = null;

    public function __construct() {
        $this->usuarios = new ArrayCollection();
        $this->playlistCanciones = new ArrayCollection();
    }

    public function getId(): int {
        return $this->id;
    }

    public function getTitulo(): string {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): self {
        $this->titulo = $titulo;
        return $this;
    }

    public function getDuracion(): int {
        return $this->duracion;
    }

    public function setDuracion(int $duracion): self {
        $this->duracion = $duracion;
        return $this;
    }

    public function getAlbum(): ?string {
        return $this->album;
    }

    public function setAlbum(?string $album): self {
        $this->album = $album;
        return $this;
    }

    public function getAutor(): string {
        return $this->autor;
    }

    public function setAutor(string $autor): self {
        $this->autor = $autor;
        return $this;
    }

    public function getLikes(): int {
        return $this->likes;
    }

    public function setLikes(int $likes): self {
        $this->likes = $likes;
        return $this;
    }

    public function getGenero(): ?Estilo {
        return $this->genero;
    }

    public function setGenero(?Estilo $genero): self {
        $this->genero = $genero;
        return $this;
    }

    public function getUsuarios(): Collection {
        return $this->usuarios;
    }

    public function getPlaylistCanciones(): Collection {
        return $this->playlistCanciones;
    }

    public function __toString()
    {
        return $this->titulo ?? "Titulo no encontrado";
    }

    public function getArchivo(): ?string
    {
        return $this->archivo;
    }

    public function setArchivo(?string $archivo): static
    {
        $this->archivo = $archivo;

        return $this;
    }

    public function getImagen(): ?string
    {
        return $this->imagen;
    }

    public function setImagen(?string $imagen): static
    {
        $this->imagen = $imagen;

        return $this;
    }
}
