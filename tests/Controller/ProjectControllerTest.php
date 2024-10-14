<?php

namespace App\Tests\Controller;

use App\Entity\Project;
use App\Entity\Company;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProjectControllerTest extends WebTestCase
{
    private ?EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        // Démarre le kernel de Symfony
        self::bootKernel();

        // Récupère l'EntityManager depuis le conteneur
        $this->entityManager = self::getContainer()->get('doctrine')->getManager();
        
        // Vérifie que l'EntityManager n'est pas nul
        if ($this->entityManager === null) {
            throw new \RuntimeException('EntityManager cannot be null');
        }
    }

    public function testCreateProject(): void
    {
        // Crée une nouvelle entreprise
        $company = new Company();
        $company->setName("Test Company")
            ->setSiret("12345678901234")
            ->setAddress("123 Test St");

        // Persiste et sauvegarde l'entreprise
        $this->entityManager->persist($company);
        $this->entityManager->flush();

        // Crée un nouveau projet
        $project = new Project();
        $project->setTitle("Test Project")
            ->setDescription("Description of the test project")
            ->setCompany($company) // Assurez-vous que la méthode existe
            ->setCreatedAt(new \DateTime());

        // Persiste et sauvegarde le projet
        $this->entityManager->persist($project);
        $this->entityManager->flush();

        // Vérifie si le projet a été créé correctement
        $this->assertNotNull($project->getId());
        $this->assertEquals("Test Project", $project->getTitle());
        $this->assertEquals("Description of the test project", $project->getDescription());
        $this->assertEquals($company, $project->getCompany());
    }

    protected function tearDown(): void
    {
        // Nettoyage après chaque test
        $this->entityManager->close();
        $this->entityManager = null; // Évite les fuites de mémoire
    }
}
