<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Company; 
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * ProjectController gère les opérations sur les entités Project.
 */
#[Route('/companies/{companyId}/projects')]
class ProjectController extends AbstractController
{
    private ProjectRepository $projectRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(ProjectRepository $projectRepository, EntityManagerInterface $entityManager)
    {
        $this->projectRepository = $projectRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route(methods={"GET"})
     *
     * Récupère tous les projets d'une société si l'utilisateur y appartient.
     */
    public function index(int $companyId): JsonResponse
    {
        // Vérifiez que l'utilisateur appartient à la société ici (ajoutez votre logique de vérification)

        // Récupérer les projets associés à la société
        $projects = $this->projectRepository->findBy(['company' => $companyId]);

        return $this->json($projects);
    }

    /**
     * @Route(methods={"POST"})
     *
     * Crée un nouveau projet dans une société si l'utilisateur a les droits.
     */
    public function create(Request $request, int $companyId): JsonResponse
    {
        // Récupérer les données JSON de la requête
        $data = json_decode($request->getContent(), true);

        // Vérifiez que l'utilisateur a le droit de créer un projet ici (ajoutez votre logique de vérification)

        // Créer une nouvelle instance de projet
        $project = new Project();
        $project->setTitle($data['title']);
        $project->setDescription($data['description']);
        $project->setCreatedAt(new \DateTime()); // Ajoutez une date de création

        // Récupérer la société en fonction de l'ID
        $company = $this->entityManager->getRepository(Company::class)->find($companyId);
        if ($company === null) {
            return $this->json(['error' => 'Company not found'], Response::HTTP_NOT_FOUND);
        }
        $project->setCompany($company);

        // Persist the entity in the database
        $this->entityManager->persist($project);
        $this->entityManager->flush();

        return $this->json($project, Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}", methods={"GET"})
     *
     * Affiche les détails d'un projet spécifique.
     */
    public function show(int $companyId, int $id): JsonResponse
    {
        // Récupérer le projet
        $project = $this->projectRepository->find($id);

        // Vérifiez que l'utilisateur a accès à ce projet
        // Vous devriez ajouter une vérification ici

        if (!$project) {
            return $this->json(['error' => 'Project not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($project);
    }

    /**
     * @Route("/{id}", methods={"PUT"})
     *
     * Met à jour un projet existant si l'utilisateur a les droits.
     */
    public function update(Request $request, int $companyId, int $id): JsonResponse
    {
        $project = $this->projectRepository->find($id);

        if (!$project) {
            return $this->json(['error' => 'Project not found'], Response::HTTP_NOT_FOUND);
        }

        // Récupérer les données JSON de la requête
        $data = json_decode($request->getContent(), true);
        $project->setTitle($data['title']);
        $project->setDescription($data['description']);

        // Update entity in the database
        $this->entityManager->flush();

        return $this->json($project);
    }

    /**
     * @Route("/{id}", methods={"DELETE"})
     *
     * Supprime un projet existant si l'utilisateur a les droits.
     */
    public function delete(int $companyId, int $id): JsonResponse
    {
        $project = $this->projectRepository->find($id);

        if (!$project) {
            return $this->json(['error' => 'Project not found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($project);
        $this->entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
