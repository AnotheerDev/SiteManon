<?php

namespace App\Controller;

use App\Repository\PostRepository;
use App\Repository\TopicRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{
    #[Route('/post', name: 'app_post')]
    public function index(): Response
    {
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
        ]);
    }

    #[Route('/post/{topicId}', name: 'app_posts')]
    public function posts(int $topicId, TopicRepository $topicRepository, PostRepository $postRepository): Response
    {
        // Récupérer l'objet Topic depuis la base de données
        $topic = $topicRepository->find($topicId);
    
        if (!$topic) {
            throw $this->createNotFoundException('Le topic demandé n\'existe pas');
        }
    
        // Récupérer les posts liés à ce topic
        $posts = $postRepository->findPostsByTopic($topicId);
    
        // Passer les posts et le nom du topic à la vue
        return $this->render('post/index.html.twig', [
            'posts' => $posts,
            'topicName' => $topic->getName(), // Ici on utilise l'objet Topic pour obtenir le nom
        ]);
    }
    
    
}
