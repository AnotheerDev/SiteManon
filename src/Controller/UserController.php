<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }


    public function deleteUser(User $user, EntityManagerInterface $entityManager): void
    {
        // Parcourir tous les commentaires de l'utilisateur et les déconnecter de l'utilisateur
        foreach ($user->getUserQuotes() as $quote) {
            $quote->setUser(null);
        }

        // Parcourir tous les posts de l'utilisateur et les déconnecter de l'utilisateur
        foreach ($user->getCreatPost() as $post) {
            $post->setUser(null);
        }

        // Parcourir tous les topics de l'utilisateur et les déconnecter de l'utilisateur
        foreach ($user->getCreatTopic() as $topic) {
            $topic->setUser(null);
        }

        // Maintenant, on peut supprimer l'utilisateur en toute sécurité
        $entityManager->remove($user);
        $entityManager->flush();
    }

}