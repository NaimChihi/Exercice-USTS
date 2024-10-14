<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProjectFunctionalTest extends WebTestCase
{
    public function testCreateProject(): void
    {
        $client = static::createClient();

        $client->request('POST', '/api/projects', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'title' => 'Functional Project Test',
            'description' => 'Functional test for creating a project.',
            'company' => '/api/companies/1'
        ]));

        $this->assertResponseStatusCodeSame(201); // HTTP_CREATED
    }
}
