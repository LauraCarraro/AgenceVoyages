<?php

namespace App\Controller\API;

use App\Repository\DestinationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/destination', name: 'api_destination_')]
class DestinationController extends AbstractController
{
    #[Route('s', name: 'index')]
    public function index(DestinationRepository $destinationRepository): Response
    {
        $destinations = $destinationRepository->findAll();
        return $this->json(data: $destinations, context: [
            'groups' => 'api_destination_index'
        ]);
    }
}
