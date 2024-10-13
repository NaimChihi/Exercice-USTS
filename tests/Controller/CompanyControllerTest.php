<?php

namespace App\Tests\Controller;

use App\Entity\Company;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CompanyControllerTest extends WebTestCase
{
    private $client; // Client pour les requêtes HTTP
    private $entityManager; // Entité manager pour gérer la base de données

    protected function setUp(): void
    {
        $this->client = static::createClient(); // Crée un client de test
        $this->entityManager = $this->client->getContainer()->get('doctrine')->getManager(); // Récupère l'entity manager
    }

    public function testCreateCompany(): void
    {
        // Envoi d'une requête POST pour créer une société
        $this->client->request('POST', '/companies', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'name' => 'New Company'
        ]));

        // Vérifiez que le code de réponse est 201 (Created)
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        // Vérifiez que la société a été créée
        $this->assertJsonStringEqualsJsonString(
            json_encode(['name' => 'New Company']),
            $this->client->getResponse()->getContent()
        );
    }

    // Ajoutez d'autres tests ici...
}
