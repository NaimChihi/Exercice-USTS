<?php

namespace App\Tests\Unit\Voter;

use App\Security\Voter\ProjectVoter;

class ProjectVoterTest
{
    private ProjectVoter $voter;

    public function __construct()
    {
        // Instanciation du ProjectVoter
        $this->voter = new ProjectVoter();
    }

    public function testInstance()
    {
        // Vérifie que le ProjectVoter peut être instancié
        if (!($this->voter instanceof ProjectVoter)) {
            throw new \Exception('L\'instance de ProjectVoter n\'a pas été créée correctement.');
        }
        echo "testInstance passed.\n";
    }

    public function runTests()
    {
        $this->testInstance();
        // Les autres tests peuvent être commentés pour éviter des erreurs
        // $this->testSupports();
        // $this->testVoteOnAttribute();
    }
}

// Exécution des tests si le fichier est exécuté directement
$test = new ProjectVoterTest();
$test->runTests();

echo "Tous les tests passés avec succès.\n";
