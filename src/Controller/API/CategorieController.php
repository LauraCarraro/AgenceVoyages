<?php

namespace App\Controller\API;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/categorie', name: 'api_categorie_')]
class CategorieController extends AbstractController
{
    #[Route('s', name: 'index')]
    public function index(CategorieRepository $categorieRepository): JsonResponse
    {
        $categories = $categorieRepository->findAll();
        return $this->json(data: $categories, context: [
            'groups' => 'api_categorie_index'
        ]);
    }

    #[Route('/{nom}', name: 'show')]
    public function show(Categorie $categorie): JsonResponse
    {
        return $this -> json($categorie, context: ['groups' => ['api_categorie_index', 'api_categorie_show']]);
    }
}
