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
            $query, /* la requête ou le tableau d'articles */
            $request->query->getInt('page', 1), /* numéro de la page en cours, 1 par défaut */
            3 /* limite par page */
        );

        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/article/{id}', name: 'app_article_show')]
    public function show(Article $article, Request $request, EntityManagerInterface $entityManager)
    {
        $quote = new Quote();
        $form = $this->createForm(QuoteType::class, $quote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $quote->setArticle($article);
            $quote->setUser($this->getUser()); // utilisateur est connecté
            $quote->setDateCreation(new \DateTime());
            $entityManager->persist($quote);
            $entityManager->flush();

            return $this->redirectToRoute('app_article_show', ['id' => $article->getId()]);
        }

        return $this->render('article/show.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }
}

