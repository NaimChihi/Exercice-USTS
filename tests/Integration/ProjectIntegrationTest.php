<?php

namespace App\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Project;
use App\Entity\Company;

class ProjectIntegrationTest extends WebTestCase
{
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        self::bootKernel(); // Démarre le noyau de Symfony
        $this->entityManager = self::$container->get(EntityManagerInterface::class); // Récupère l'entity manager
    }

    public function testPersistProject(): void
    {
        // Crée une société pour le test
        $company = new Company();
        $company->setName('Integration Test Company');

        $this->entityManager->persist($company);
        $this->entityManager->flush();

        // Crée un nouveau projet
        $project = new Project();
        $project->setTitle('Integration Test Project');
        $project->setDescription('Project description');
        $project->setCompany($company); // Associe le projet à la société

        // Persiste le projet
        $this->entityManager->persist($project);
        $this->entityManager->flush();

        // Vérifiez que le projet a bien été persisté dans la base de données
        $this->assertNotNull($project->getId());
    }
}
