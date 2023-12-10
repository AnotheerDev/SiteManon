<?php

namespace App\Controller;

use App\Entity\Topic;
use App\Form\TopicType;
use App\Repository\TopicRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


use Symfony\Component\HttpFoundation\JsonResponse;
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

    #[Route('/topic/{id}/click', name: 'topic_click', methods: ['POST'])]
    public function incrementTopicClickCount(Topic $topic, EntityManagerInterface $entityManager): JsonResponse
    {
        // Incrémente le compteur de clics
        $currentClickCount = $topic->getClickCount();
        $topic->setClickCount($currentClickCount + 1);
    
        // Sauvegarde les changements dans la base de données
        $entityManager->persist($topic);
        $entityManager->flush();
    
        // Retourne le nouveau compteur de clics en tant que réponse JSON
        return new JsonResponse(['clickCount' => $topic->getClickCount()]);
    }
    


    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/topic/{categoryId}', name: 'app_topics')]
    public function topics(int $categoryId, CategoryRepository $categoryRepository, TopicRepository $topicRepository, Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {
        // Récupérer l'objet Category depuis la base de données
        $category = $categoryRepository->find($categoryId);
    
        if (!$category) {
            throw $this->createNotFoundException('La catégorie demandée n\'existe pas');
        }
    
        // Créer un nouveau topic et le formulaire associé
        $topic = new Topic();
        $topic->setClickCount(0); // Définir le compteur de clics à 0 par défaut
        $form = $this->createForm(TopicType::class, $topic);
        $form->handleRequest($request);

        // Récupérer les topics liés à cette catégorie avec pagination
        $queryBuilder = $topicRepository->createQueryBuilder('t')
        ->where('t.categoryTopic = :category')
        ->setParameter('category', $category)
        ->orderBy('t.dateCreation', 'DESC');

        $pagination = $paginator->paginate(
            $queryBuilder, 
            $request->query->getInt('page', 1), // Numéro de la page en cours, 1 par défaut
            6 // Nombre de résultats par page
        );
    
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->security->getUser(); // Récupérer l'utilisateur connecté
            $topic->setUser($user); // Associer l'utilisateur au topic
            $topic->setDateCreation(new \DateTime()); // Définir la date de création
            $topic->setLocked(false); // Définir le statut verrouillé à false par défaut
            $topic->setCategoryTopic($category); // Associer la catégorie au topic
    
            $entityManager->persist($topic);
            $entityManager->flush();
    
            // Redirection ou affichage d'un message de succès
            return $this->redirectToRoute('app_posts', ['topicId' => $topic->getId()]);
        }
    
        // Récupérer les topics liés à cette catégorie
        $topics = $topicRepository->findTopicsByCategory($categoryId);
    
        // Passer les topics, le nom de la catégorie et le formulaire à la vue
        return $this->render('topic/index.html.twig', [
            'pagination' => $pagination,
            'topics' => $topics,
            'categoryName' => $category->getName(), // Ici l'objet Category pour obtenir le nom
            'categories' => $categoryRepository->findAll(),
            'form' => $form->createView(), // Ajouter cette ligne pour passer le formulaire à la vue
        ]);
    }
}
