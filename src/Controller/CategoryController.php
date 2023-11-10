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
        $categories = $categoryRepository->findAllCategories();
        $mostClickedTopics = $topicRepository->findMostClickedTopics();
        $topics = $topicRepository->findLatestTopics();


        return $this->render('category/index.html.twig', [
            'categories' => $categories,
            'mostClickedTopics' => $mostClickedTopics,
            'topics' => $topics,
        ]);
    }
}
