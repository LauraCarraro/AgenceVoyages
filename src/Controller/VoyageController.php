<?php

namespace App\Controller;

use App\Entity\Voyage;
use App\Form\VoyageType;
use App\Repository\VoyageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('/voyage', name: 'app_voyage_')]
class VoyageController extends AbstractController
{
    #[Route('s', name: 'index', methods: ['GET'])]
    public function index(VoyageRepository $voyageRepository): Response
    {
        return $this->render('voyage/index.html.twig', [
            'voyages' => $voyageRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $voyage = new Voyage();
        $form = $this->createForm(VoyageType::class, $voyage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $voyage->setUser($this->getUser());
            $entityManager->persist($voyage);
            $entityManager->flush();

            return $this->redirectToRoute('app_voyage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('voyage/new.html.twig', [
            'voyage' => $voyage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Voyage $voyage): Response
    {
        return $this->render('voyage/show.html.twig', [
            'voyage' => $voyage,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UserInterface $user, Voyage $voyage, EntityManagerInterface $entityManager): Response
    {
        if ($this->isGranted('ROLE_USER') && !$this->isGranted('ROLE_ADMIN')) {
            
            if ($voyage->getUser() !== $user) {
                $this->addFlash('error', 'Vous ne pouvez pas modifier ce voyage.');
                return $this->redirectToRoute('app_voyage_index');
            }
        }
        $form = $this->createForm(VoyageType::class, $voyage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_voyage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('voyage/edit.html.twig', [
            'voyage' => $voyage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, UserInterface $user,Voyage $voyage, EntityManagerInterface $entityManager): Response
    {
        if ($this->isGranted('ROLE_USER') && !$this->isGranted('ROLE_ADMIN')) {
            
            if ($voyage->getUser() !== $user) {
                $this->addFlash('error', 'Vous ne pouvez pas supprimer ce voyage.');
                return $this->redirectToRoute('app_voyage_index');
            }

        if ($this->isCsrfTokenValid('delete'.$voyage->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($voyage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_voyage_index', [], Response::HTTP_SEE_OTHER);
    }
}}
