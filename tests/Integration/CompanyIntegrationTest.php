<?php

namespace App\Tests\Integration;

use App\Entity\Company;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CompanyIntegrationTest extends KernelTestCase
{
    public function testPersistCompany(): void
    {
        self::bootKernel();
        $container = self::$kernel->getContainer();
        $entityManager = $container->get('doctrine')->getManager();

        $company = new Company();
        $company->setName('Integrated Company');

        $entityManager->persist($company);
        $entityManager->flush();

        $repository = $entityManager->getRepository(Company::class);
        $retrievedCompany = $repository->findOneBy(['name' => 'Integrated Company']);

        $this->assertNotNull($retrievedCompany);
        $this->assertSame('Integrated Company', $retrievedCompany->getName());
    }
}
