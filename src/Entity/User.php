<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'app_user')] 
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ApiResource]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    // Identifiant unique de l'utilisateur
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Adresse e-mail de l'utilisateur (doit être unique)
    #[ORM\Column(length: 180)]
    private ?string $email = null;

    // Les rôles de l'utilisateur sous forme de tableau de chaînes
    #[ORM\Column]
    private array $roles = [];

    // Mot de passe haché de l'utilisateur
    #[ORM\Column]
    private ?string $password = null;

    // Collection des entreprises associées à l'utilisateur
    #[ORM\ManyToMany(targetEntity: Company::class, mappedBy: 'users')]
    private Collection $companies;

    // Collection des UserCompany associant l'utilisateur aux entreprises
    #[ORM\OneToMany(targetEntity: UserCompany::class, mappedBy: 'userAccount', orphanRemoval: true)]
    private Collection $userCompanies;

    // Constructeur de la classe User
    public function __construct()
    {
        $this->companies = new ArrayCollection();
        $this->userCompanies = new ArrayCollection();
    }

    // Obtient l'identifiant de l'utilisateur
    public function getId(): ?int
    {
        return $this->id;
    }

    // Obtient l'adresse e-mail de l'utilisateur
    public function getEmail(): ?string
    {
        return $this->email;
    }

    // Définit l'adresse e-mail de l'utilisateur
    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    // Obtient l'identifiant utilisateur (utilisé par Symfony)
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    // Obtient les rôles de l'utilisateur (avec ROLE_USER par défaut)
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    // Définit les rôles de l'utilisateur
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    // Obtient le mot de passe de l'utilisateur
    public function getPassword(): ?string
    {
        return $this->password;
    }

    // Définit le mot de passe de l'utilisateur
    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    // Méthode requise par UserInterface (peut être utilisée pour effacer des données sensibles)
    public function eraseCredentials(): void
    {
    }

    // Obtient la collection des entreprises associées à l'utilisateur
    public function getCompanies(): Collection
    {
        return $this->companies;
    }

    // Ajoute une entreprise à la collection des entreprises de l'utilisateur
    public function addCompany(Company $company): static
    {
        if (!$this->companies->contains($company)) {
            $this->companies->add($company);
            $company->addUser($this);
        }
        return $this;
    }

    // Retire une entreprise de la collection des entreprises de l'utilisateur
    public function removeCompany(Company $company): static
    {
        if ($this->companies->removeElement($company)) {
            $company->removeUser($this);
        }
        return $this;
    }

    // Obtient la collection des UserCompany associant l'utilisateur aux entreprises
    public function getUserCompanies(): Collection
    {
        return $this->userCompanies;
    }

    // Ajoute une UserCompany à la collection des UserCompany de l'utilisateur
    public function addUserCompany(UserCompany $userCompany): static
    {
        if (!$this->userCompanies->contains($userCompany)) {
            $this->userCompanies->add($userCompany);
            $userCompany->setUserAccount($this);
        }
        return $this;
    }

    // Retire une UserCompany de la collection des UserCompany de l'utilisateur
    public function removeUserCompany(UserCompany $userCompany): static
    {
        if ($this->userCompanies->removeElement($userCompany)) {
            if ($userCompany->getUserAccount() === $this) {
                $userCompany->setUserAccount(null);
            }
        }
        return $this;
    }

    // Récupère la liste des sociétés associées à l'utilisateur via UserCompany
    public function getCompaniesList(): Collection
    {
        $companies = new ArrayCollection();
        foreach ($this->userCompanies as $userCompany) {
            $companies->add($userCompany->getCompany());
        }
        return $companies;
    }
}
