<?php

namespace App\Controller;

use App\Entity\Company;
use App\Repository\CompanyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CompanyController extends AbstractController
{
    /**
     * @Route("/companies", name="company_list", methods={"GET"})
     *
     * Affiche la liste des entreprises associées à l'utilisateur connecté.
     *
     * @param CompanyRepository $companyRepository
     * @return Response
     */
    public function list(CompanyRepository $companyRepository): Response
    {
        // Récupère l'utilisateur connecté
        $user = $this->getUser();

        // Vérifie que l'utilisateur est bien une instance de App\Entity\User et qu'il a des entreprises associées
        if (!$user instanceof \App\Entity\User || !$user->getUserCompanies()->count()) {
            throw new AccessDeniedException('Vous n\'avez pas accès à des sociétés.');
        }

        // Récupère la liste des entreprises de l'utilisateur via la méthode getCompaniesList
        $companies = $user->getCompaniesList();

        // Retourne la liste des entreprises en format JSON
        return $this->json($companies);
    }

    /**
     * @Route("/companies/{id}", name="company_detail", methods={"GET"})
     *
     * Affiche les détails d'une entreprise spécifique associée à l'utilisateur connecté.
     *
     * @param Company $company
     * @return Response
     */
    public function detail(Company $company): Response
    {
        // Récupère l'utilisateur connecté
        $user = $this->getUser();

        // Vérifie que l'utilisateur est bien une instance de App\Entity\User et qu'il a accès à cette entreprise
        if (!$user instanceof \App\Entity\User || !$user->getCompaniesList()->contains($company)) {
            throw new AccessDeniedException('Vous n\'avez pas accès à cette société.');
        }

        // Retourne les détails de l'entreprise en format JSON
        return $this->json($company);
    }
}
