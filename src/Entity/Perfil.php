<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
class Perfil {
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $foto = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $descripcion = null;

    #[ORM\OneToOne(mappedBy: 'perfil', targetEntity: Usuario::class)]
    private ?Usuario $usuario = null;

    /**
     * @var Collection<int, Estilo>
     */
    #[ORM\ManyToMany(targetEntity: Estilo::class, inversedBy: 'perfils')]
    private Collection $estilosMusicalesPreferidos;

    public function __construct()
    {
        $this->estilosMusicalesPreferidos = new ArrayCollection();
    }


    public function getId(): int {
        return $this->id;
    }

    public function getFoto(): ?string {
        return $this->foto;
    }

    public function setFoto(?string $foto): self {
        $this->foto = $foto;
        return $this;
    }

    public function getDescripcion(): ?string {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): self {
        $this->descripcion = $descripcion;
        return $this;
    }


    public function getUsuario(): ?Usuario {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): self {
        $this->usuario = $usuario;
        return $this;
    }

    /**
     * @return Collection<int, Estilo>
     */
    public function getEstilosMusicalesPreferidos(): Collection
    {
        return $this->estilosMusicalesPreferidos;
    }

    public function addEstilosMusicalesPreferido(Estilo $estilosMusicalesPreferido): static
    {
        if (!$this->estilosMusicalesPreferidos->contains($estilosMusicalesPreferido)) {
            $this->estilosMusicalesPreferidos->add($estilosMusicalesPreferido);
        }

        return $this;
    }

    public function removeEstilosMusicalesPreferido(Estilo $estilosMusicalesPreferido): static
    {
        $this->estilosMusicalesPreferidos->removeElement($estilosMusicalesPreferido);

        return $this;
    }
}
