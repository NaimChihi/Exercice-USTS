<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

// Déclare l'entité Company et configure son utilisation avec API Platform
#[ORM\Entity(repositoryClass: CompanyRepository::class)]
#[ApiResource(
    security: "is_granted('ROLE_USER')", // Sécurise l'accès aux ressources à ceux qui ont le rôle ROLE_USER
    operations: [
        new \ApiPlatform\Metadata\GetCollection( // Opération pour récupérer la collection d'entreprises
            security: "is_granted('ROLE_USER')" // Sécurise l'opération
        ),
        new \ApiPlatform\Metadata\Post( // Opération pour créer une nouvelle entreprise
            security: "is_granted('ROLE_USER')" // Sécurise l'opération
        ),
    ]
)]
class Company
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null; // Nom de l'entreprise

    #[ORM\Column(length: 14)]
    private ?string $siret = null; // SIRET de l'entreprise

    #[ORM\Column(length: 255)]
    private ?string $address = null; // Adresse de l'entreprise

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'companies')]
    private Collection $users; // Utilisateurs associés à l'entreprise

    #[ORM\OneToMany(targetEntity: Project::class, mappedBy: 'company')]
    private Collection $projects; // Projets associés à l'entreprise

    #[ORM\OneToMany(targetEntity: UserCompany::class, mappedBy: 'company', orphanRemoval: true)]
    private Collection $userCompanies; // Associations utilisateur-entreprise

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->projects = new ArrayCollection();
        $this->userCompanies = new ArrayCollection();
    }

    // Méthodes pour accéder et modifier les propriétés
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(string $siret): static
    {
        $this->siret = $siret;
        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;
        return $this;
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
        }
        return $this;
    }

    public function removeUser(User $user): static
    {
        $this->users->removeElement($user);
        return $this;
    }

    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): static
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
            $project->setCompany($this);
        }
        return $this;
    }

    public function removeProject(Project $project): static
    {
        if ($this->projects->removeElement($project)) {
            if ($project->getCompany() === $this) {
                $project->setCompany(null);
            }
        }
        return $this;
    }

    public function getUserCompanies(): Collection
    {
        return $this->userCompanies;
    }

    public function addUserCompany(UserCompany $userCompany): static
    {
        if (!$this->userCompanies->contains($userCompany)) {
            $this->userCompanies->add($userCompany);
            $userCompany->setCompany($this);
        }
        return $this;
    }

    public function removeUserCompany(UserCompany $userCompany): static
    {
        if ($this->userCompanies->removeElement($userCompany)) {
            if ($userCompany->getCompany() === $this) {
                $userCompany->setCompany(null);
            }
        }
        return $this;
    }
}
