<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Entity\Company;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CompanyControllerTest extends WebTestCase
{
    private $client;
    private $entityManager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = $this->client->getContainer()->get('doctrine')->getManager();
    }

    public function testCreateCompany(): void
    {
        $user = new User();
        $user->setEmail('test@example.com');
        $user->setPassword('password'); // Assurez-vous d'ajouter un mot de passe

        // Assurez-vous que l'utilisateur est enregistré dans la base de données
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // Simuler l'authentification de l'utilisateur
        $this->client->loginUser($user);

        // Effectuer la requête POST pour créer une nouvelle entreprise
        $this->client->request('POST', '/companies', [
            'json' => ['name' => 'New Company'] // Utilisation de 'json' pour spécifier le type de contenu
        ]);

        // Vérifiez que la réponse est un code 201 Created
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        // Vérifiez que l'entreprise a bien été créée
        $company = $this->entityManager->getRepository(Company::class)->findOneBy(['name' => 'New Company']);
        $this->assertNotNull($company);
    }
}
