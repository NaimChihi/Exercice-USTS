<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class ProtectedController extends AbstractController
{
    /**
     * @Route("/api/protected-route", name="api_protected_route", methods={"GET"})
     * @IsGranted("ROLE_USER") // Cela nécessite un utilisateur authentifié avec le rôle USER
     */
    public function protectedRoute(): JsonResponse
    {
        return new JsonResponse(['message' => 'This is a protected route.']);
    }
}



