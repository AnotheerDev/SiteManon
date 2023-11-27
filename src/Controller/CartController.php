<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
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
    public function addToCart(int $id, Request $request, SessionInterface $session, ProductRepository $productRepository): Response
    {
        // Récupérer le produit par son id
        $product = $productRepository->find($id);
    
        // Si le produit n'existe pas, renvoyer une erreur ou rediriger
        if (!$product) {
            throw $this->createNotFoundException('Le produit demandé n\'existe pas.');
        }
    
        // Récupérer la quantité du formulaire, avec une valeur par défaut de 1 si aucune quantité n'est fournie
        $quantity = $request->request->getInt('quantity', 1);
    
        // Obtenir le panier actuel de la session, ou initialiser à un tableau vide si aucun panier n'existe
        $cart = $session->get('cart', []);
    
        // Vérifier si le produit est déjà dans le panier
        if (isset($cart[$id])) {
            // Augmenter la quantité si le produit est déjà présent
            $cart[$id]['quantity'] += $quantity;
        } else {
            // Ajouter le produit avec la quantité spécifiée s'il n'est pas déjà dans le panier
            $cart[$id] = [
                'quantity' => $quantity,
                'product' => $product
            ];
        }
    
        // Enregistrer le panier mis à jour dans la session
        $session->set('cart', $cart);
    
        // Ajouter un message flash pour notifier l'utilisateur
        $this->addFlash('success', 'Produit ajouté au panier.');
    
        // Rediriger vers la page du panier
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



    #[Route('/cart/checkout', name: 'cart_checkout')]
    public function checkout(
        Request $request,
        SessionInterface $session,
        ProductRepository $productRepository,
        EntityManagerInterface $entityManager
    ): Response {
        // Vérifiez que le panier n'est pas vide
        $cart = $session->get('cart', []);
        if (empty($cart)) {
            $this->addFlash('warning', 'Votre panier est vide.');
            return $this->redirectToRoute('app_product'); // Rediriger vers la page de la boutique
        }

        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer l'utilisateur connecté
            $user = $this->getUser();
            if ($user) {
                $commande->setUser($user);
            }
            // Créer et ajouter les objets Cart à la commande
            foreach ($cart as $id => $item) {
                $cartItem = new Cart();
                $product = $productRepository->find($id);
                if (!$product) {
                    // Gérer l'erreur si le produit n'est pas trouvé
                    continue; // ou traiter l'erreur comme il convient
                }
                $cartItem->setProduct($product);
                $cartItem->setQuantity($item['quantity']);
                $cartItem->setPrice($product->getPrice());
                $cartItem->setCommande($commande);
                $entityManager->persist($cartItem);
            }
            $entityManager->persist($commande);
            $entityManager->flush();

            // Nettoyer le panier de la session
            $session->remove('cart');

            // Permet de récupérer les produits de la commande et les afficher dans la page de confirmation pour stripe
            $lineItems = [];
            foreach ($cart as $id => $item) {
                $product = $productRepository->find($id);
                if (!$product) {
                    // Si le produit n'est pas trouvé, on peut gérer cette erreur.
                    continue;
                }
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'eur',
                        'unit_amount' => $product->getPrice() * 100, // Le prix doit être en centimes pour Stripe
                        'product_data' => [
                            'name' => $product->getName(),
                            // on peut ajouter d'autres détails du produit ici si nécessaire.
                        ],
                    ],
                    'quantity' => $item['quantity'],
                ];
            }
            
            $stripe_sk = $this->getParameter('stripe_sk');
            \Stripe\Stripe::setApiKey($stripe_sk);
            

            $checkout_session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => $this->generateUrl('checkout_success', ['id' => $commande->getId()], UrlGeneratorInterface::ABSOLUTE_URL),
                'cancel_url' => $this->generateUrl('checkout_cancel', [], UrlGeneratorInterface::ABSOLUTE_URL),
            ]);
            
        
            // Redirigez l'utilisateur vers la session Stripe Checkout
            return $this->redirect($checkout_session->url);
        }

        // Afficher le formulaire si celui-ci n'a pas encore été soumis, ou s'il n'est pas valide
        return $this->render('cart/checkout.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    #[Route('/checkout/success/{id}', name: 'checkout_success')]
    public function checkoutSuccess($id, EntityManagerInterface $entityManager): Response {
        // Récupérer la commande à partir de l'ID
        $commande = $entityManager->getRepository(Commande::class)->find($id);
    
        // Vérifier si la commande existe
        if (!$commande) {
            throw $this->createNotFoundException('Commande non trouvée.');
        }
    
        $total = 0;
        foreach ($commande->getCarts() as $cart) {
            $total += $cart->getPrice() * $cart->getQuantity();
        }

        // Passer l'entité commande au template
        return $this->render('checkout/success.html.twig', [
            'commande' => $commande,
            'total' => $total
        ]);
    }



    #[Route('/checkout/cancel', name: 'checkout_cancel')]
    public function checkoutCancel(): Response
    {
        // Logique pour traiter une commande annulée
        $this->addFlash('error', 'Le paiement a été annulé.');

        return $this->redirectToRoute('cart');
    }
}
