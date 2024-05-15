<?php

namespace App\Controller\API;

use App\Entity\Destination;
use App\Repository\DestinationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/destination', name: 'api_destination_')]
class DestinationController extends AbstractController
{
    #[Route('s', name: 'index')]
    public function index(DestinationRepository $destinationRepository): JsonResponse
    {
        $destinations = $destinationRepository->findAll();
        return $this->json(data: $destinations, context: [
            'groups' => 'api_destination_index'
        ]);
    }

    #[Route('/{nom}', name: 'show')]
    public function show(Destination $destination): JsonResponse
    {
        return $this -> json($destination, context: ['groups' => ['api_destination_index', 'api_destination_show']]);
    }
}
