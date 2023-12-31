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
            $query, // la requête  d'articles 
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

    #[Route('/quote/edit/{id}', name: 'app_quote_edit')]
    public function editquote(Request $request, EntityManagerInterface $entityManager, Quote $quote): Response {
        // check si l'utilisateur est autorisé à modifier le commentaire
        if ($this->getUser() !== $quote->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à modifier ce commentaire.');
        }

        $form = $this->createForm(QuoteType::class, $quote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Le commentaire a été modifié avec succès.');

            return $this->redirectToRoute('app_article_show', ['id' => $quote->getArticle()->getId()]);
        }

        return $this->render('quote/edit.html.twig', [
            'form' => $form->createView(),
            'quote' => $quote,
        ]);
    }

    #[Route('/quote/delete/{id}', name: 'app_quote_delete')]
    public function deletequote(EntityManagerInterface $entityManager, Quote $quote): Response {
        // chack si l'utilisateur est autorisé à supprimer le commentaire
        if ($this->getUser() !== $quote->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à supprimer ce commentaire.');
        }

        $articleId = $quote->getArticle()->getId();
        $entityManager->remove($quote);
        $entityManager->flush();

        $this->addFlash('success', 'Le commentaire a été supprimé.');

        return $this->redirectToRoute('app_article_show', ['id' => $articleId]);
    }

}

