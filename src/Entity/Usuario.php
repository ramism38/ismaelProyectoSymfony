<?php
namespace App\Entity;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class Usuario implements UserInterface, PasswordAuthenticatedUserInterface {

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    #[ORM\Column(length: 180)]
    private ?string $email = null;
    
    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;
    
    #[ORM\Column(type: 'string')]
    private string $nombre;
    
    #[ORM\Column(type: 'string')]
    private string $fechaNacimiento;
    
    #[ORM\OneToOne(targetEntity: Perfil::class, cascade: ['persist', 'remove'])]
    private ?Perfil $perfil = null;
    
    #[ORM\OneToMany(mappedBy: 'usuarioPropietario', targetEntity: Playlist::class)]
    private Collection $playlists;
    
    #[ORM\ManyToMany(targetEntity: Cancion::class)]
    private Collection $canciones;

    /**
     * @var Collection<int, UsuarioListenPlaylist>
     */
    #[ORM\OneToMany(targetEntity: UsuarioListenPlaylist::class, mappedBy: 'usuario')]
    private Collection $usuarioListenPlaylists;
    
    public function __construct() {
        $this->playlists = new ArrayCollection();
        $this->canciones = new ArrayCollection();
        $this->usuarioListenPlaylists = new ArrayCollection();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getEmail(): string {
        return $this->email;
    }
    
    public function setEmail(string $email): self {
        $this->email = $email;
        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        //$roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }
    
    public function getPassword(): string {
        return $this->password;
    }
    
    public function setPassword(string $password): self {
        $this->password = $password;
        return $this;
    }

        /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
    
    public function getNombre(): string {
        return $this->nombre;
    }
    
    public function setNombre(string $nombre): self {
        $this->nombre = $nombre;
        return $this;
    }
    
    public function getFechaNacimiento(): string {
        return $this->fechaNacimiento;
    }
    
    public function setFechaNacimiento(string $fechaNacimiento): self {
        $this->fechaNacimiento = $fechaNacimiento;
        return $this;
    }
    
    public function getPerfil(): ?Perfil {
        return $this->perfil;
    }
    
    public function setPerfil(?Perfil $perfil): self {
        $this->perfil = $perfil;
        return $this;
    }
    
    public function getPlaylists(): Collection {
        return $this->playlists;
    }
    
    public function addPlaylist(Playlist $playlist): self {
        if (!$this->playlists->contains($playlist)) {
            $this->playlists->add($playlist);
            $playlist->setUsuarioPropietario($this);
        }
        return $this;
    }
    
    public function removePlaylist(Playlist $playlist): self {
        if ($this->playlists->removeElement($playlist)) {
            if ($playlist->getUsuarioPropietario() === $this) {
                $playlist->setUsuarioPropietario(null);
            }
        }
        return $this;
    }
    
    public function getCanciones(): Collection {
        return $this->canciones;
    }
    
    public function addCancion(Cancion $cancion): self {
        if (!$this->canciones->contains($cancion)) {
            $this->canciones->add($cancion);
        }
        return $this;
    }
    
    public function removeCancion(Cancion $cancion): self {
        $this->canciones->removeElement($cancion);
        return $this;
    }

    /**
     * @return Collection<int, UsuarioListenPlaylist>
     */
    public function getUsuarioListenPlaylists(): Collection
    {
        return $this->usuarioListenPlaylists;
    }

    public function addUsuarioListenPlaylist(UsuarioListenPlaylist $usuarioListenPlaylist): static
    {
        if (!$this->usuarioListenPlaylists->contains($usuarioListenPlaylist)) {
            $this->usuarioListenPlaylists->add($usuarioListenPlaylist);
            $usuarioListenPlaylist->setUsuario($this);
        }

        return $this;
    }

    public function removeUsuarioListenPlaylist(UsuarioListenPlaylist $usuarioListenPlaylist): static
    {
        if ($this->usuarioListenPlaylists->removeElement($usuarioListenPlaylist)) {
            // set the owning side to null (unless already changed)
            if ($usuarioListenPlaylist->getUsuario() === $this) {
                $usuarioListenPlaylist->setUsuario(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->nombre ?? "Nombre no encontrado";
    }
}
