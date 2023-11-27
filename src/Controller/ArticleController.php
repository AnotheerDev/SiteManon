<?php

namespace App\Controller;

use App\Entity\Quote;
use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\QuoteType;

class ArticleController extends AbstractController
{
    #[Route('/article', name: 'app_article')]
    public function index(ArticleRepository $articleRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $articleRepository->findAllArticlesQuery();

        $articles = $paginator->paginate(
            $query, // la requête ou le tableau d'articles 
            $request->query->getInt('page', 1), // numéro de la page en cours, 1 par défaut 
            3 // limite par page 
        );

        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/article/{id}', name: 'app_article_show')]
    public function show(Article $article, Request $request, EntityManagerInterface $entityManager)
    {
        // Création d'un nouvel objet Quote pour les commentaires
        $quote = new Quote();

        // Création du formulaire de commentaire
        $form = $this->createForm(QuoteType::class, $quote);
        $form->handleRequest($request);

        // Vérification si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Association de l'article et de l'utilisateur actuellement connecté au commentaire
            $quote->setArticle($article);
            $quote->setUser($this->getUser()); // Récupération de l'utilisateur connecté

            // Définition de la date de création du commentaire
            $quote->setDateCreation(new \DateTime());

            // Enregistrement du commentaire dans la base de données
            $entityManager->persist($quote);
            $entityManager->flush();

            // Redirection vers la même page de l'article pour voir le commentaire ajouté
            return $this->redirectToRoute('app_article_show', ['id' => $article->getId()]);
        }

        // Rendu de la page de l'article avec le formulaire de commentaire
        return $this->render('article/show.html.twig', [
            'article' => $article, // Passage de l'article à la vue
            'form' => $form->createView(), // Passage du formulaire à la vue
        ]);
    }
}

