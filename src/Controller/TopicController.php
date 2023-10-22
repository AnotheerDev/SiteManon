<?php

namespace App\Controller;

use App\Repository\TopicRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TopicController extends AbstractController
{
    #[Route('/topic', name: 'app_topic')]
    public function index(): Response
    {
        return $this->render('topic/index.html.twig', [
            'controller_name' => 'TopicController',
        ]);
    }


    #[Route('/topic/{categoryId}', name: 'app_topics')]

    public function topics(int $categoryId, CategoryRepository $categoryRepository, TopicRepository $topicRepository): Response
    {
        // Récupérer l'objet Category depuis la base de données
        $category = $categoryRepository->find($categoryId);
    
        if (!$category) {
            throw $this->createNotFoundException('La catégorie demandée n\'existe pas');
        }
    
        // Récupérer les topics liés à cette catégorie
        $topics = $topicRepository->findTopicsByCategory($categoryId);
    
        // Passer les topics et le nom de la catégorie à la vue
        return $this->render('topic/index.html.twig', [
            'topics' => $topics,
            'categoryName' => $category->getName(), // Ici l'objet Category pour obtenir le nom
        ]);
    }
}
