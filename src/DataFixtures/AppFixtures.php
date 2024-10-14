<?php

namespace App\DataFixtures;

use App\Entity\Company;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Créer une entreprise
        $company = new Company();
        $company->setName('Test Company');
        $manager->persist($company);

        // Créer un utilisateur
        $user = new User();
        $user->setEmail('testuser@example.com');
        $user->setPassword('testpassword');
        $user->setRoles(['ROLE_USER']);
        $manager->persist($user);

        $manager->flush();

        // Stockez l'entreprise et l'utilisateur pour les utiliser dans d'autres fixtures si nécessaire
        $this->addReference('company_test', $company);
        $this->addReference('user_test', $user);
    }
}
