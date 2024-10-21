<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ProjectRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

// Annotation pour indiquer que cette classe est une entité Doctrine
#[ORM\Entity(repositoryClass: ProjectRepository::class)]
// Configuration pour l'utilisation avec API Platform
#[ApiResource(
    security: "is_granted('ROLE_USER')"
)]
class Project
{
    // Identifiant unique de l'entité
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Titre du projet
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    // Description du projet
    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    // Date de création du projet
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    // Relation ManyToOne vers l'entité Company
    #[ORM\ManyToOne(inversedBy: 'projects')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Company $company = null;

    // Constructeur pour initialiser la date de création
    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    // Getter pour l'ID
    public function getId(): ?int
    {
        return $this->id;
    }

    // Getter pour le titre
    public function getTitle(): ?string
    {
        return $this->title;
    }

    // Setter pour le titre
    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    // Getter pour la description
    public function getDescription(): ?string
    {
        return $this->description;
    }

    // Setter pour la description
    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    // Getter pour la date de création
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    // Setter pour la date de création
    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    // Getter pour la compagnie
    public function getCompany(): ?Company
    {
        return $this->company;
    }

    // Setter pour la compagnie
    public function setCompany(?Company $company): static
    {
        $this->company = $company;
        return $this;
    }
}
