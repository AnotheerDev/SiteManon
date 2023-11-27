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
        $products = $productRepository->findAllProducts();

        $cart = $session->get('cart', []);
        $itemCount = 0;
        foreach ($cart as $item) {
            $itemCount += $item['quantity'];
        }

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'itemCount' => $itemCount,
        ]);
    }
}
