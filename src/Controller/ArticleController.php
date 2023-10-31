<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
}

