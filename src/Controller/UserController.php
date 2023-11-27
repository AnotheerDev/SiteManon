<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
         // Obtient l'utilisateur actuellement connecté
        $user = $this->getUser();
        $commandes = $user->getCommander();

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }
    
        return $this->render('user/index.html.twig', [
            'user' => $user,
            'commandes' => $commandes,
        ]);
    }
    


    #[Route('/user/edit', name: 'app_edit_profile')]
    public function editProfile(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(EditProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //les données mises à jour
            $newData = [
                'nickname' => $user->getNickname(),
                'email' => $user->getEmail(),
            ];

            // Appel de la méthode de mise à jour du repository
            $entityManager->getRepository(User::class)->updateUserData($user->getId(), $newData);

            // Redirection ou gestion de la réponse
            return $this->redirectToRoute('app_user');
        }

        // Affichage du formulaire
        return $this->render('user/edit.html.twig', [
            'editForm' => $form->createView(),
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
