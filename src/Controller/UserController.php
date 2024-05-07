<?php

namespace App\Controller;

use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[IsGranted('ROLE_USER')]
#[Route('/user', name: 'app_user_')]
class UserController extends AbstractController
{
    #[Route('s', name: 'index')]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {   
        if ($this->isGranted('ROLE_USER') && !$this->isGranted('ROLE_ADMIN')) {
        
                $this->addFlash('errorUser', 'Vous ne pouvez pas créer un utilisateur.');
                return $this->redirectToRoute('app_user_index');
            }
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $utilisateurConnecte = $this->getUser();
        if ($this->isGranted('ROLE_USER') && !$this->isGranted('ROLE_ADMIN')&& $user !== $utilisateurConnecte) {
        
                $this->addFlash('errorUser', 'Vous ne pouvez pas modifier cet utilisateur.');
                return $this->redirectToRoute('app_user_index');
            }
        
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $utilisateurConnecte = $this->getUser();
        if ($this->isGranted('ROLE_USER') && !$this->isGranted('ROLE_ADMIN')&& $user !== $utilisateurConnecte) {
        
                $this->addFlash('errorUser', 'Vous ne pouvez pas supprimer cet utilisateur.');
                return $this->redirectToRoute('app_user_index');
            }
        
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
