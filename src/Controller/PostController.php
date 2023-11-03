<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Repository\TopicRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
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

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/post/{topicId}', name: 'app_posts', methods: ['GET', 'POST'])]
    public function posts(
        int $topicId,
        TopicRepository $topicRepository,
        PostRepository $postRepository,
        CategoryRepository $categoryRepository,
        Request $request,
        EntityManagerInterface $entityManager,
        Security $security
    ): Response {
        $topic = $topicRepository->find($topicId);
    
        if (!$topic) {
            throw $this->createNotFoundException('Le topic demandé n\'existe pas');
        }
    
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->security->getUser(); // Récupérer l'utilisateur connecté
            $post->setUser($user); // Associer l'utilisateur au topic
            $post->setTopicPost($topic);
            $post->setDateCreation(new \DateTime()); // Définir la date de création
            // Set other required fields for the Post entity...
    
            $entityManager->persist($post);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_posts', ['topicId' => $topicId]);
        }
    
        $posts = $postRepository->findPostsByTopic($topicId);
    
        return $this->render('post/index.html.twig', [
            'posts' => $posts,
            'topicName' => $topic->getName(),
            'categories' => $categoryRepository->findAll(),
            'form' => $form->createView(),
        ]);
    }
    
    
    
    
}
