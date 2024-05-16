<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le prenom ne doit pas être vide.')]
    #[Assert\Length(min: 3, max: 255, minMessage: "Le prenom doit contenir au moins {{ limit }} caractères.", maxMessage: "Le prenom ne doit pas contenir plus de {{ limit }} caractères.")]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le nom ne doit pas être vide.')]
    #[Assert\Length(min: 3, max: 255, minMessage: "Le nom doit contenir au moins {{ limit }} caractères.", maxMessage: "Le nom ne doit pas contenir plus de {{ limit }} caractères.")]
    private ?string $nom = null;
    
    #[ORM\Column(length: 180)]
    #[Assert\NotBlank(message: 'L\'adresse mail ne doit pas être vide.')]
    #[Assert\Email(message:"L'adresse e-mail n'est pas valide.")]
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
    #[Assert\NotBlank(message: 'Le mot de passe ne doit pas être vide.')]
    private ?string $password = null;

    

    /**
     * @var Collection<int, Voyage>
     */
    #[ORM\OneToMany(targetEntity: Voyage::class, mappedBy: 'user')]
    private Collection $Voyage;

    #[ORM\Column]
    private bool $isVerified = false;

    public function __construct()
    {
        $this->Voyage = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
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
        $roles[] = 'ROLE_USER';

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

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection<int, Voyage>
     */
    public function getVoyage(): Collection
    {
        return $this->Voyage;
    }

    public function addVoyage(Voyage $voyage): static
    {
        if (!$this->Voyage->contains($voyage)) {
            $this->Voyage->add($voyage);
            $voyage->setUser($this);
        }

        return $this;
    }

    public function removeVoyage(Voyage $voyage): static
    {
        if ($this->Voyage->removeElement($voyage)) {
            // set the owning side to null (unless already changed)
            if ($voyage->getUser() === $this) {
                $voyage->setUser(null);
            }
        }

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }
}
