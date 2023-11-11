<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'cart')]
    public function cart(SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);
    
        // Calculer le total, etc.
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['product']->getPrice() * $item['quantity'];
        }
    
        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
            'total' => $total
        ]);
    }

    #[Route('/add/{id}', name: 'cart_add')]
    public function addToCart(int $id, SessionInterface $session, ProductRepository $productRepository): Response
    {
        // Récupérer le produit par son id
        $product = $productRepository->find($id);

        // Si le produit n'existe pas, renvoyer une erreur ou rediriger
        if (!$product) {
            throw $this->createNotFoundException('Le produit demandé n\'existe pas.');
        }

        // Obtenir le panier actuel de la session, ou initialiser à un tableau vide si aucun panier n'existe
        $cart = $session->get('cart', []);

        // Vérifier si le produit est déjà dans le panier
        if (!empty($cart[$id])) {
            // Augmenter la quantité si le produit est déjà présent
            $cart[$id]['quantity']++;
        } else {
            // Ajouter le produit avec une quantité de 1 s'il n'est pas déjà dans le panier
            $cart[$id] = [
                'quantity' => 1,
                'product' => $product
            ];
        }

        // Enregistrer le panier mis à jour dans la session
        $session->set('cart', $cart);

        // Ajouter un message flash pour notifier l'utilisateur
        $this->addFlash('success', 'Produit ajouté au panier.');

        // Rediriger vers la page précédente ou vers le panier
        return $this->redirectToRoute('cart');
    }

    #[Route('/cart/update/{id}', name: 'update_cart')]
    public function updateCart(int $id, Request $request, SessionInterface $session): Response
    {
        $quantity = $request->request->getInt('quantity');

        $csrfToken = $request->request->get('_csrf_token');
        if (!$this->isCsrfTokenValid('update-cart-item' . $id, $csrfToken)) {
            // Ajoute un message flash d'erreur
            $this->addFlash('error', 'Votre session a expiré ou les données soumises ne sont pas valides.');
    
            // Redirige l'utilisateur vers la page du panier pour qu'il puisse essayer à nouveau
            return $this->redirectToRoute('cart');
    
            // Ou sinon , renvoyer une réponse d'erreur HTTP
            // throw new HttpException(400, 'Invalid CSRF token.');
        }
        // Validation basique de la quantité
        if ($quantity < 1) {
            // Gérer l'erreur, par exemple, ajouter un message flash ou renvoyer une réponse d'erreur
            $this->addFlash('error', 'La quantité doit être positive.');
            return $this->redirectToRoute('cart');
        }

        // Récupérer le panier de la session
        $cart = $session->get('cart', []);

        // Si le produit est dans le panier, mettre à jour la quantité
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $quantity;
            $session->set('cart', $cart);
        }

        // Rediriger vers la page du panier
        return $this->redirectToRoute('cart');
    }

    #[Route('/cart/remove/{id}', name: 'remove_from_cart')]
    public function removeFromCart(int $id, SessionInterface $session): Response
    {
        // Récupérer le panier de la session
        $cart = $session->get('cart', []);

        // Si le produit est dans le panier, le supprimer
        if (isset($cart[$id])) {
            unset($cart[$id]);
            $session->set('cart', $cart);
        }

        // Rediriger vers la page du panier
        return $this->redirectToRoute('cart');
    }

}
