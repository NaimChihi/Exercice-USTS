<?php

namespace App\Repository;

use App\Entity\Company;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Company>
 *
 * Cette classe permet d'interagir avec la base de données pour l'entité Company.
 * Elle étend ServiceEntityRepository qui fournit des méthodes de base pour manipuler les entités.
 */
class CompanyRepository extends ServiceEntityRepository
{
    /**
     * CompanyRepository constructor.
     *
     * @param ManagerRegistry $registry Le registre du gestionnaire de Doctrine.
     */
    public function __construct(ManagerRegistry $registry)
    {
        // Appelle le constructeur parent avec le registre et la classe Company
        parent::__construct($registry, Company::class);
    }

    /**
     * Trouver une entreprise par son ID.
     *
     * @param int $id L'identifiant de l'entreprise.
     * @param int|null $lockMode Le mode de verrouillage (facultatif).
     * @param int|null $lockVersion La version de verrouillage (facultatif).
     * @return Company|null Retourne l'entité Company si trouvée, sinon null.
     */
    public function find($id, $lockMode = null, $lockVersion = null): ?Company
    {
        return parent::find($id, $lockMode, $lockVersion);
    }

    /**
     * Trouver une entreprise selon des critères spécifiques.
     *
     * @param array $criteria Les critères de recherche.
     * @param array|null $orderBy Les critères de tri (facultatif).
     * @return Company|null Retourne l'entité Company si trouvée, sinon null.
     */
    public function findOneBy(array $criteria, array $orderBy = null): ?Company
    {
        return parent::findOneBy($criteria, $orderBy);
    }

    /**
     * Trouver toutes les entreprises.
     *
     * @return Company[] Retourne un tableau d'entités Company.
     */
    public function findAll(): array
    {
        return parent::findAll();
    }

    /**
     * Trouver des entreprises selon des critères spécifiques.
     *
     * @param array $criteria Les critères de recherche.
     * @param array|null $orderBy Les critères de tri (facultatif).
     * @param int|null $limit Limite le nombre de résultats (facultatif).
     * @param int|null $offset Décale les résultats (facultatif).
     * @return Company[] Retourne un tableau d'entités Company.
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null): array
    {
        return parent::findBy($criteria, $orderBy, $limit, $offset);
    }

    // Ajoutez ici des méthodes personnalisées si nécessaire
}
