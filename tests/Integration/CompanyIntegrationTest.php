<?php

namespace App\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Company;

class CompanyIntegrationTest extends WebTestCase
{
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        self::bootKernel(); // Démarre le noyau de Symfony
        $this->entityManager = self::$container->get(EntityManagerInterface::class); // Récupère l'entity manager
    }

    public function testPersistCompany(): void
    {
        // Crée une nouvelle société
        $company = new Company();
        $company->setName('Integration Test Company');

        // Persiste la société
        $this->entityManager->persist($company);
        $this->entityManager->flush();

        // Vérifiez que la société a bien été persistée dans la base de données
        $this->assertNotNull($company->getId());
    }
}
