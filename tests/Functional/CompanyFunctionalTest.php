<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CompanyFunctionalTest extends WebTestCase
{
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testCreateCompany(): void
    {
        set_exception_handler(null);

        $this->client->request('POST', '/companies', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'name' => 'Functional Company'
        ]));

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertJsonStringEqualsJsonString(
            json_encode(['name' => 'Functional Company']),
            $this->client->getResponse()->getContent()
        );

        restore_exception_handler();
    }
}
