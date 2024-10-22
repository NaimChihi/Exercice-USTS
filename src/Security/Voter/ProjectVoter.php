<?php

namespace App\Security\Voter;

use App\Entity\Project;
use App\Entity\User; 
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ProjectVoter extends Voter
{
    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, ['view', 'edit'])
            && $subject instanceof Project;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        switch ($attribute) {
            case 'view':
                return true; // Exemple : tous les utilisateurs peuvent voir
            case 'edit':
                return false; // Exemple : seuls les administrateurs peuvent éditer
        }

        return false;
    }
}
