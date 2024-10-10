<?php

namespace App\Security\Voter;

use App\Entity\Project;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ProjectVoter extends Voter
{
    // Constants pour les attributs d'accès
    const VIEW = 'view';
    const CREATE = 'create';
    const EDIT = 'edit';
    const DELETE = 'delete';

    // Vérifie si l'attribut et le sujet sont supportés
    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::VIEW, self::CREATE, self::EDIT, self::DELETE])
            && $subject instanceof Project;
    }

    // Vérifie l'autorisation sur l'attribut spécifié
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // Vérifie si l'utilisateur est connecté
        if (!$user instanceof User) {
            return false;
        }

        // Récupérer le rôle de l'utilisateur pour la société associée
        $project = $subject;
        $company = $project->getCompany(); // On suppose que le projet a une méthode getCompany()
        $userRole = $this->getUserRoleInCompany($user, $company);

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($userRole);

            case self::CREATE:
                return $this->canCreate($userRole);

            case self::EDIT:
                return $this->canEdit($userRole);

            case self::DELETE:
                return $this->canDelete($userRole);
        }

        return false;
    }

    // Logique pour récupérer le rôle de l'utilisateur dans la société
    private function getUserRoleInCompany(User $user, $company): ?string
    {
        foreach ($user->getUserCompanies() as $userCompany) {
            if ($userCompany->getCompany()->getId() === $company->getId()) {
                return $userCompany->getRole(); // Retourner le rôle de l'utilisateur dans cette société
            }
        }

        return null; // Si l'utilisateur n'est pas membre de la société
    }

    // Méthodes pour les différentes actions
    private function canView(?string $userRole): bool
    {
        return $userRole !== null; // Tout utilisateur membre de la société peut voir le projet
    }

    private function canCreate(?string $userRole): bool
    {
        return in_array($userRole, ['admin', 'manager']); // admin et manager peuvent créer
    }

    private function canEdit(?string $userRole): bool
    {
        return in_array($userRole, ['admin', 'manager']); // admin et manager peuvent modifier
    }

    private function canDelete(?string $userRole): bool
    {
        return $userRole === 'admin'; // Seul un admin peut supprimer
    }
}
