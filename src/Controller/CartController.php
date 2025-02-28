<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Article;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart_index')]
    public function index(SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);
        $total = 0;

        // Calculer le total
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
            'total' => $total
        ]);
    }

    #[Route('/cart/add/{id}', name: 'app_cart_add', methods: ['POST'])]
    public function add(Article $article, Request $request, SessionInterface $session): Response
    {
        // Récupérer les données du formulaire
        $size = $request->request->get('size');
        $quantity = (int) $request->request->get('quantity');

        // Récupérer le panier actuel
        $cart = $session->get('cart', []);

        // Créer une clé unique pour cet article avec cette taille
        $key = $article->getId() . '-' . $size;

        // Si l'article existe déjà dans le panier, augmenter la quantité
        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $quantity;
        } else {
            // Sinon, ajouter le nouvel article
            $cart[$key] = [
                'id' => $article->getId(),
                'name' => $article->getNom(),
                'price' => $article->getPrix(),
                'size' => $size,
                'quantity' => $quantity,
                'image' => $article->getImage()
            ];
        }

        // Sauvegarder le panier mis à jour
        $session->set('cart', $cart);

        // Ajouter un message flash
        $this->addFlash('success', 'Article ajouté au panier !');

        // Rediriger vers la page de l'article
        return $this->redirectToRoute('app_article_details', ['id' => $article->getId()]);
    }

    #[Route('/cart/update/{id}/{size}', name: 'app_cart_update', methods: ['POST'])]
    public function update(string $id, string $size, Request $request, SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);
        $key = $id . '-' . $size;
        $quantity = (int) $request->request->get('quantity');

        if (isset($cart[$key])) {
            if ($quantity > 0) {
                $cart[$key]['quantity'] = $quantity;
                $this->addFlash('success', 'Quantité mise à jour');
            } else {
                unset($cart[$key]);
                $this->addFlash('success', 'Article supprimé du panier');
            }
            $session->set('cart', $cart);
        }

        return $this->redirectToRoute('app_cart_index');
    }

    #[Route('/cart/validate', name: 'app_cart_validate')]
    public function validate(Request $request, SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);
        $total = 0;

        // Calculer le total
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $user = $this->getUser();

        if ($request->isMethod('POST')) {
            // Simulate payment processing
            $paymentMethod = $request->request->get('payment_method');
            if ($paymentMethod === 'credit_card') {
                $cardNumber = $request->request->get('card_number');
                $expiryDate = $request->request->get('expiry_date');
                $cvv = $request->request->get('cvv');
                // Here you would normally process the payment with a payment gateway
            } elseif ($paymentMethod === 'paypal') {
                $paypalEmail = $request->request->get('paypal_email');
                // Here you would normally process the payment with PayPal
            }

            if ($user->getBalance() < $total) {
                $this->addFlash('error', 'Vous n\'avez pas assez de crédit pour passer la commande.');
                return $this->redirectToRoute('app_cart_index');
            }

            // Déduire le total du solde de l'utilisateur
            $user->setBalance($user->getBalance() - $total);

            // Vider le panier
            $session->set('cart', []);

            $this->addFlash('success', 'Commande validée avec succès !');

            return $this->render('cart/validate.html.twig', [
                'total' => $total,
            ]);
        }

        return $this->render('cart/validate.html.twig', [
            'total' => $total,
        ]);
    }
}