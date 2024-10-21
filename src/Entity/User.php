<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

// Déclare l'entité User et configure son utilisation avec API Platform
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'app_user')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ApiResource]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null; // Identifiant unique de l'utilisateur

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null; // Adresse email de l'utilisateur

    #[ORM\Column]
    private array $roles = []; // Rôles associés à l'utilisateur

    #[ORM\Column]
    private ?string $password = null; // Mot de passe de l'utilisateur

    #[ORM\ManyToMany(targetEntity: Company::class, mappedBy: 'users')]
    private Collection $companies; // Liste des entreprises associées à l'utilisateur

    #[ORM\OneToMany(targetEntity: UserCompany::class, mappedBy: 'userAccount', orphanRemoval: true)]
    private Collection $userCompanies; // Liste des relations UserCompany associées à l'utilisateur

    // Constructeur pour initialiser les collections
    public function __construct()
    {
        $this->companies = new ArrayCollection();
        $this->userCompanies = new ArrayCollection();
    }

    // Getter pour l'ID
    public function getId(): ?int
    {
        return $this->id;
    }

    // Getter et Setter pour l'email
    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    // Méthode pour obtenir l'identifiant utilisateur (UserIdentifier)
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    // Getter et Setter pour les rôles
    public function getRoles(): array
    {
        $roles = $this->roles;
        // Chaque utilisateur a au moins le rôle ROLE_USER
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    // Getter et Setter pour le mot de passe
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    // Méthode pour effacer les informations sensibles de l'utilisateur
    public function eraseCredentials(): void
    {
        // Si vous stockez des données sensibles temporaires, les supprimer ici
    }

    // Gestion de la relation ManyToMany avec l'entité Company
    public function getCompanies(): Collection
    {
        return $this->companies;
    }

    public function addCompany(Company $company): static
    {
        if (!$this->companies->contains($company)) {
            $this->companies->add($company);
            $company->addUser($this);
        }
        return $this;
    }

    public function removeCompany(Company $company): static
    {
        if ($this->companies->removeElement($company)) {
            $company->removeUser($this);
        }
        return $this;
    }

    // Gestion de la relation OneToMany avec l'entité UserCompany
    public function getUserCompanies(): Collection
    {
        return $this->userCompanies;
    }

    public function addUserCompany(UserCompany $userCompany): static
    {
        if (!$this->userCompanies->contains($userCompany)) {
            $this->userCompanies->add($userCompany);
            $userCompany->setUserAccount($this);
        }
        return $this;
    }

    public function removeUserCompany(UserCompany $userCompany): static
    {
        if ($this->userCompanies->removeElement($userCompany)) {
            // Définir la propriété owning side à null (sauf si elle est déjà modifiée)
            if ($userCompany->getUserAccount() === $this) {
                $userCompany->setUserAccount(null);
            }
        }
        return $this;
    }

    // Méthode pour obtenir la liste des entreprises via UserCompany
    public function getCompaniesList(): Collection
    {
        $companies = new ArrayCollection();
        foreach ($this->userCompanies as $userCompany) {
            $companies->add($userCompany->getCompany());
        }
        return $companies;
    }
}
