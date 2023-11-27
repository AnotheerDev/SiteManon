<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    #[Route('/shop', name: 'app_product')]
    public function index(ProductRepository $productRepository, SessionInterface $session): Response
    {
        //ici on récupère tous les produits
        $products = $productRepository->findAllProducts();

        //ici on récupère ce qu'il y a en session pour le panier
        $cart = $session->get('cart', []);
        $itemCount = 0;
        foreach ($cart as $item) {
            $itemCount += $item['quantity'];
        }

        //ici on affiche la vue en lui passant les produits et le nombre d'articles dans le panier
        return $this->render('product/index.html.twig', [
            'products' => $products,
            'itemCount' => $itemCount,
        ]);
    }
}
