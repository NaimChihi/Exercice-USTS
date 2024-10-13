<?php

namespace App\Tests\Controller;

use App\Entity\Project;
use App\Entity\Company;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ProjectControllerTest extends WebTestCase
{
    private $client; // Client pour les requêtes HTTP
    private $entityManager; // Entité manager pour gérer la base de données

    protected function setUp(): void
    {
        $this->client = static::createClient(); // Crée un client de test
        $this->entityManager = $this->client->getContainer()->get('doctrine')->getManager(); // Récupère l'entity manager
    }

    public function testCreateProject(): void
    {
        // Crée une société pour le test
        $company = new Company();
        $company->setName('Test Company');
        $this->entityManager->persist($company);
        $this->entityManager->flush(); // Sauvegarde la société

        // Envoi d'une requête POST pour créer un projet
        $this->client->request('POST', '/companies/' . $company->getId() . '/projects', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'title' => 'New Project',
            'description' => 'Project description'
        ]));

        // Vérifiez que le code de réponse est 201 (Created)
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        // Vérifiez que le projet a été créé
        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'title' => 'New Project',
                'description' => 'Project description',
            ]),
            $this->client->getResponse()->getContent()
        );
    }

    // Ajoutez d'autres tests ici...
}
