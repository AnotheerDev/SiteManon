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
use Knp\Component\Pager\PaginatorInterface;


class PostController extends AbstractController
{
    #[Route('/post', name: 'app_post')]
    public function index(): Response
    {
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
        ]);
    }


    // je passe en paramètre de la méthode le service Security pour récupérer l'utilisateur connecté
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
        Security $security,
        PaginatorInterface $paginator
    ): Response {
        $topic = $topicRepository->find($topicId);
    
        if (!$topic) {
            throw $this->createNotFoundException('Le topic demandé n\'existe pas');
        }
    
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
    
        // Obtenez la requête, pas les résultats
        $query = $postRepository->findPostsByTopicQuery($topicId);
    
        // Paginer les résultats de la requête
        $pagination = $paginator->paginate(
            $query, //query NOT result 
            $request->query->getInt('page', 1), // numéro de la page
            5 // limite par page 
        );
    
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $security->getUser(); // Récupérer l'utilisateur connecté
            $post->setUser($user); // Associer l'utilisateur au post
            $post->setTopicPost($topic);
            $post->setDateCreation(new \DateTime()); // Définir la date de création
    
            $entityManager->persist($post);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_posts', ['topicId' => $topicId]);
        }
    
        return $this->render('post/index.html.twig', [
            'pagination' => $pagination, // Passe la pagination à la vue
            'topicName' => $topic->getName(),
            'categories' => $categoryRepository->findAll(),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/post/edit/{id}', name: 'app_post_edit')]
    public function editPost(Request $request, EntityManagerInterface $entityManager, Post $post): Response {
        if ($this->getUser() !== $post->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à modifier ce post.');
        }

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Le post a été modifié avec succès.');
            return $this->redirectToRoute('app_posts', ['topicId' => $post->getTopicPost()->getId()]);
        }

        return $this->render('post/edit.html.twig', [
            'form' => $form->createView(),
            'post' => $post,
        ]);
    }

    #[Route('/post/delete/{id}', name: 'app_post_delete')]
    public function deletePost(EntityManagerInterface $entityManager, Post $post): Response {
        if ($this->getUser() !== $post->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à modifier ce post.');
        }

        $entityManager->remove($post);
        $entityManager->flush();
        $this->addFlash('success', 'Le post a été supprimé.');

        return $this->redirectToRoute('app_posts', ['topicId' => $post->getTopicPost()->getId()]);
    }
}
