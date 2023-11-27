<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\TopicRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(CategoryRepository $categoryRepository, TopicRepository $topicRepository): Response
    {
        // Récupération de toutes les catégories via le CategoryRepository
        $categories = $categoryRepository->findAllCategories();

        // Récupération des sujets les plus cliqués via le TopicRepository
        $mostClickedTopics = $topicRepository->findMostClickedTopics();

        // Récupération des derniers sujets via le TopicRepository
        $topics = $topicRepository->findLatestTopics();

        // Rendu du template Twig pour l'index des catégories
        // Passage des catégories, des sujets les plus cliqués et des derniers sujets à la vue
        return $this->render('category/index.html.twig', [
            'categories' => $categories,
            'mostClickedTopics' => $mostClickedTopics,
            'topics' => $topics,
        ]);
    }
}
