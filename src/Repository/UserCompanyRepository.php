<?php

namespace App\Repository;

use App\Entity\UserCompany;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserCompany>
 *
 * Cette classe permet d'interagir avec la base de données pour l'entité UserCompany.
 * Elle étend ServiceEntityRepository qui fournit des méthodes de base pour manipuler les entités.
 */
class UserCompanyRepository extends ServiceEntityRepository
{
    /**
     * UserCompanyRepository constructor.
     *
     * @param ManagerRegistry $registry Le registre du gestionnaire de Doctrine.
     */
    public function __construct(ManagerRegistry $registry)
    {
        // Appelle le constructeur parent avec le registre et la classe UserCompany
        parent::__construct($registry, UserCompany::class);
    }

    /**
     * Trouver une association utilisateur-entreprise par son ID.
     *
     * @param int $id L'identifiant de l'association.
     * @param int|null $lockMode Le mode de verrouillage (facultatif).
     * @param int|null $lockVersion La version de verrouillage (facultatif).
     * @return UserCompany|null Retourne l'entité UserCompany si trouvée, sinon null.
     */
    public function find($id, $lockMode = null, $lockVersion = null): ?UserCompany
    {
        return parent::find($id, $lockMode, $lockVersion);
    }

    /**
     * Trouver une association selon des critères spécifiques.
     *
     * @param array $criteria Les critères de recherche.
     * @param array|null $orderBy Les critères de tri (facultatif).
     * @return UserCompany|null Retourne l'entité UserCompany si trouvée, sinon null.
     */
    public function findOneBy(array $criteria, array $orderBy = null): ?UserCompany
    {
        return parent::findOneBy($criteria, $orderBy);
    }

    /**
     * Trouver toutes les associations utilisateur-entreprise.
     *
     * @return UserCompany[] Retourne un tableau d'entités UserCompany.
     */
    public function findAll(): array
    {
        return parent::findAll();
    }

    /**
     * Trouver des associations selon des critères spécifiques.
     *
     * @param array $criteria Les critères de recherche.
     * @param array|null $orderBy Les critères de tri (facultatif).
     * @param int|null $limit Limite le nombre de résultats (facultatif).
     * @param int|null $offset Décale les résultats (facultatif).
     * @return UserCompany[] Retourne un tableau d'entités UserCompany.
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null): array
    {
        return parent::findBy($criteria, $orderBy, $limit, $offset);
    }


}

