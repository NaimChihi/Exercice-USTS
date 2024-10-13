<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CompanyFunctionalTest extends WebTestCase
{
    public function testCreateCompany(): void
    {
        $client = static::createClient(); // Crée un client de test

        // Envoi d'une requête POST pour créer une société
        $client->request('POST', '/companies', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'name' => 'Functional Test Company'
        ]));

        // Vérifiez que le code de réponse est 201 (Created)
        $this->assertResponseStatusCodeSame(201);
        
        // Vérifiez que la réponse contient le nom de la société
        $this->assertJsonStringEqualsJsonString(
            json_encode(['name' => 'Functional Test Company']),
            $client->getResponse()->getContent()
        );
    }
}
