<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProjectFunctionalTest extends WebTestCase
{
    public function testCreateProject(): void
    {
        $client = static::createClient(); // Crée un client de test
        
        // Crée une société pour le test
        $client->request('POST', '/companies', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'name' => 'Functional Test Company'
        ]));

        $companyId = json_decode($client->getResponse()->getContent(), true)['id']; // Récupère l'ID de la société créée

        // Envoi d'une requête POST pour créer un projet
        $client->request('POST', '/companies/' . $companyId . '/projects', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'title' => 'Functional Test Project',
            'description' => 'Functional test description'
        ]));

        // Vérifiez que le code de réponse est 201 (Created)
        $this->assertResponseStatusCodeSame(201);

        // Vérifiez que la réponse contient les détails du projet
        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'title' => 'Functional Test Project',
                'description' => 'Functional test description',
            ]),
            $client->getResponse()->getContent()
        );
    }
}
