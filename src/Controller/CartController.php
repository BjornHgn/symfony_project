<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Article;
use App\Entity\User;
use App\Entity\Invoice;
use App\Entity\InvoiceItem;
use App\Repository\ArticleRepository;
use App\Repository\StockRepository;
use Doctrine\ORM\EntityManagerInterface;

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
    public function validate(
        SessionInterface $session,
        ArticleRepository $articleRepository,
        StockRepository $stockRepository
    ): Response {
        /** @var User $user */
        $user = $this->getUser();
        
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $cart = $session->get('cart', []);
        $cartData = [];
        $total = 0;

        foreach ($cart as $key => $item) {
            list($articleId, $size) = explode('-', $key);
            $article = $articleRepository->find($articleId);
            if ($article) {
                $cartData[] = [
                    'article' => $article,
                    'quantity' => $item['quantity'],
                    'size' => $item['size']
                ];
                $total += $article->getPrix() * $item['quantity'];
            }
        }

        return $this->render('cart/validate.html.twig', [
            'cart' => $cartData,
            'total' => $total,
            'user' => $user
        ]);
    }

    #[Route('/cart/validate/purchase', name: 'app_validate_purchase', methods: ['POST'])]
    public function validatePurchase(
        SessionInterface $session,
        ArticleRepository $articleRepository,
        StockRepository $stockRepository,
        EntityManagerInterface $entityManager
    ): Response {
        /** @var User $user */
        $user = $this->getUser();
        
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $cart = $session->get('cart', []);
        $total = 0;

        // Calculer le total et vérifier le stock
        foreach ($cart as $key => $item) {
            list($articleId, $size) = explode('-', $key);
            $article = $articleRepository->find($articleId);
            if ($article) {
                $stock = $stockRepository->findOneBy(['article' => $article]);
                if (!$stock || $stock->getNbrArticle() < $item['quantity']) {
                    $this->addFlash('error', 'Stock insuffisant pour ' . $article->getNom());
                    return $this->redirectToRoute('app_cart_validate');
                }
                $total += $article->getPrix() * $item['quantity'];
            }
        }

        // Vérifier le solde
        if ($user->getBalance() < $total) {
            $this->addFlash('error', 'Solde insuffisant');
            return $this->redirectToRoute('app_cart_validate');
        }

        // Créer la facture
        $invoice = new Invoice();
        $invoice->setUser($user);
        $invoice->setUserId($user->getId());
        $invoice->setDealDate(new \DateTime());
        $invoice->setAmount((string)$total);
        $invoice->setFacturationAddress($user->getFacturationAddress() ?? '');
        $invoice->setFacturationCity($user->getFacturationCity() ?? '');
        $invoice->setFacturationZipcode($user->getFacturationZipcode() ?? 0);
        
        $entityManager->persist($invoice);

        // Procéder à l'achat
        foreach ($cart as $key => $item) {
            list($articleId, $size) = explode('-', $key);
            $article = $articleRepository->find($articleId);
            if ($article) {
                $stock = $stockRepository->findOneBy(['article' => $article]);
                $stock->setNbrArticle($stock->getNbrArticle() - $item['quantity']);
                $entityManager->persist($stock);

                $invoiceItem = new InvoiceItem();
                $invoiceItem->setInvoice($invoice);
                $invoiceItem->setArticle($article);
                $invoiceItem->setQuantity($item['quantity']);
                $invoiceItem->setPrice((string)$article->getPrix());
                
                $invoice->addItem($invoiceItem);
            }
        }

        // Mettre à jour le solde de l'utilisateur
        $user->setBalance($user->getBalance() - $total);
        $entityManager->persist($user);
        $entityManager->flush();

        // Vider le panier
        $session->set('cart', []);

        $this->addFlash('success', 'Achat effectué avec succès ! Une facture a été générée dans votre profil.');
        return $this->redirectToRoute('app_profile');
    }
}