<?php

namespace App\Controller;

use App\Repository\PostRepository;
use App\Repository\TopicRepository;
use App\Repository\CategoryRepository;
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
    public function posts(int $topicId, TopicRepository $topicRepository, PostRepository $postRepository, CategoryRepository $categoryRepository): Response
    {
        // Récupérer l'objet Topic depuis la base de données
        $topic = $topicRepository->find($topicId);
    
        if (!$topic) {
            throw $this->createNotFoundException('Le topic demandé n\'existe pas');
        }
    
        // Récupérer les posts liés à ce topic
        $posts = $postRepository->findPostsByTopic($topicId);
    
        // Passer les posts, le nom du topic, et les catégories à la vue
        return $this->render('post/index.html.twig', [
            'posts' => $posts,
            'topicName' => $topic->getName(),
            'categories' => $categoryRepository->findAll(),
        ]);
    }
    
    
    
}
