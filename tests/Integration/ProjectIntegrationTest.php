<?php

namespace App\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Entity\Project; // Assurez-vous que cette importation est correcte
use App\Entity\Company; // Ajoutez cette ligne pour importer la classe Company

class ProjectIntegrationTest extends KernelTestCase
{
    public function testPersistProject(): void
    {
        self::bootKernel();
        $entityManager = static::getContainer()->get('doctrine')->getManager();

        // Assurez-vous que l'entreprise est déjà en base de données
        $company = $entityManager->getRepository(Company::class)->find(1);
        
        // Si l'entreprise n'existe pas, vous pourriez vouloir créer une nouvelle entreprise ici
        if (!$company) {
            $company = new Company();
            $company->setName('Default Test Company');
            $company->setSiret('12345678901234'); // Assurez-vous d'utiliser une valeur valide pour siret
            $entityManager->persist($company);
            $entityManager->flush();
        }

        $project = new Project();
        $project->setTitle('Integration Test Project');
        $project->setDescription('This is a project created during integration testing.');
        $project->setCreatedAt(new \DateTime());
        $project->setCompany($company);

        $entityManager->persist($project);
        $entityManager->flush();

        $this->assertNotNull($project->getId());
    }
}
