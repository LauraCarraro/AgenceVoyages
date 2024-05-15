<?php

namespace App\Controller\API;

use App\Entity\Voyage;
use App\Repository\VoyageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/voyage', name: 'api_voyage_')]
class VoyageController extends AbstractController
{
    #[Route('s', name: 'index')]
    public function index(VoyageRepository $voyageRepository): JsonResponse
    {
        $voyages = $voyageRepository->findAll();
        return $this->json(data: $voyages, context: [
            'groups' => 'api_voyage_index'
        ]);
    }
   #[Route('/{id}', name: 'show')]
    public function show(EntityManagerInterface $entityManager, $id): JsonResponse
    {
       $repo = $entityManager->getRepository(Voyage::class);
       $voyage = $repo->findOneBy(['nom' => $id]);
       return $this -> json($voyage, context: ['groups' => ['api_voyage_index', 'api_voyage_show']]);
    }
}
