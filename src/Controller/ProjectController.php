<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Company;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ProjectController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/api/projects", name="project_create", methods={"POST"})
     */
    public function create(Request $request): JsonResponse
    {
        try {
            // Décoder le contenu JSON de la requête en tableau associatif
            $data = json_decode($request->getContent(), true);

            // Vérifier si le titre du projet est présent
            if (empty($data['title'])) {
                return $this->json(['error' => 'Title is required'], Response::HTTP_BAD_REQUEST);
            }

            // Vérifier si l'ID de l'entreprise est présent
            if (empty($data['companyId'])) {
                return $this->json(['error' => 'Company ID is required'], Response::HTTP_BAD_REQUEST);
            }

            // Récupérer l'entreprise depuis la base de données
            $company = $this->entityManager->getRepository(Company::class)->find($data['companyId']);
            if (!$company) {
                return $this->json(['error' => 'Company not found'], Response::HTTP_NOT_FOUND);
            }

            // Créer un nouvel objet Project
            $project = new Project();
            $project->setTitle($data['title']);
            $project->setDescription($data['description'] ?? '');
            $project->setCompany($company); // Association de l'entreprise au projet

            // Vérifier que l'utilisateur est connecté
            $user = $this->getUser();
            if (!$user) {
                throw new AccessDeniedException('Vous devez être connecté pour créer un projet.');
            }

            // Assignation de la date de création
            $project->setCreatedAt(new \DateTime());

            // Persistance du projet dans la base de données
            $this->entityManager->persist($project);
            $this->entityManager->flush();

            // Retourner une réponse JSON avec le projet créé et le statut 201 (Created)
            return $this->json($project, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            // En cas d'exception, retourner une réponse JSON avec le message d'erreur
            return $this->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
