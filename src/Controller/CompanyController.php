<?php

namespace App\Controller;

use App\Entity\Company;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CompanyController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/companies", name="company_list", methods={"GET"})
     */
    public function list(CompanyRepository $companyRepository): Response
    {
        $user = $this->getUser();

        if (!$user instanceof \App\Entity\User || !$user->getUserCompanies()->count()) {
            throw new AccessDeniedException('Vous n\'avez pas accès à des sociétés.');
        }

        $companies = $user->getCompaniesList();

        return $this->json($companies);
    }

    /**
     * @Route("/companies/{id}", name="company_detail", methods={"GET"})
     */
    public function detail(Company $company): Response
    {
        $user = $this->getUser();

        if (!$user instanceof \App\Entity\User || !$user->getCompaniesList()->contains($company)) {
            throw new AccessDeniedException('Vous n\'avez pas accès à cette société.');
        }

        return $this->json($company);
    }

    /**
     * @Route("/companies", name="company_create", methods={"POST"})
     */
    public function create(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            if (empty($data['name'])) {
                return $this->json(['error' => 'Name is required'], Response::HTTP_BAD_REQUEST);
            }

            $company = new Company();
            $company->setName($data['name']);

            $user = $this->getUser();
            if (!$user) {
                throw new AccessDeniedException('Vous devez être connecté pour créer une entreprise.');
            }

            $company->addUser($user);

            $this->entityManager->persist($company);
            $this->entityManager->flush();

            return $this->json($company, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
