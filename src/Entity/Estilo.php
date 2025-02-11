<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
class Estilo {
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', unique: true)]
    private string $nombre;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $descripcion = null;

    #[ORM\OneToMany(mappedBy: 'genero', targetEntity: Cancion::class)]
    private Collection $canciones;

    #[ORM\ManyToMany(targetEntity: Perfil::class, mappedBy: 'estilosMusicalPreferidos')]
    private Collection $perfiles;

    /**
     * @var Collection<int, Perfil>
     */
    #[ORM\ManyToMany(targetEntity: Perfil::class, mappedBy: 'estilosMusicalesPreferidos')]
    private Collection $perfils;

    public function __construct() {
        $this->canciones = new ArrayCollection();
        $this->perfiles = new ArrayCollection();
        $this->perfils = new ArrayCollection();
    }

    public function getId(): int {
        return $this->id;
    }

    public function getNombre(): string {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self {
        $this->nombre = $nombre;
        return $this;
    }

    public function getDescripcion(): ?string {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): self {
        $this->descripcion = $descripcion;
        return $this;
    }

    public function getCanciones(): Collection {
        return $this->canciones;
    }

    public function addCancion(Cancion $cancion): self {
        if (!$this->canciones->contains($cancion)) {
            $this->canciones->add($cancion);
            $cancion->setGenero($this);
        }
        return $this;
    }

    public function removeCancion(Cancion $cancion): self {
        if ($this->canciones->removeElement($cancion)) {
            if ($cancion->getGenero() === $this) {
                $cancion->setGenero(null);
            }
        }
        return $this;
    }

    public function getPerfiles(): Collection {
        return $this->perfiles;
    }

    /**
     * @return Collection<int, Perfil>
     */
    public function getPerfils(): Collection
    {
        return $this->perfils;
    }

    public function addPerfil(Perfil $perfil): static
    {
        if (!$this->perfils->contains($perfil)) {
            $this->perfils->add($perfil);
            $perfil->addEstilosMusicalesPreferido($this);
        }

        return $this;
    }

    public function removePerfil(Perfil $perfil): static
    {
        if ($this->perfils->removeElement($perfil)) {
            $perfil->removeEstilosMusicalesPreferido($this);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->nombre ?? "Nombre no encontrado";
    }
}
