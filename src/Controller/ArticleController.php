<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\StockRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Stock;
use App\Form\ArticleType;

class ArticleController extends AbstractController
{
    #[Route('/article/{id}', name: 'app_article_details')]
    public function details(Article $article, StockRepository $stockRepository): Response
    {
        $stock = $stockRepository->findOneBy(['article' => $article]);
        
        dump($article);
        dump($stock);
        dump($stock ? $stock->getNbrArticle() : 0);

        return $this->render('articles/details.html.twig', [
            'article' => $article,
            'stockQuantity' => $stock ? $stock->getNbrArticle() : 0
        ]);
    }

    #[Route('/sell', name: 'app_add_article')]
    public function addArticle(Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setCreatedAt(new \DateTime());
            
            // Définir l'auteur
            $user = $this->getUser();
            if (!$user) {
                $this->addFlash('error', 'Vous devez être connecté pour vendre un article.');
                return $this->redirectToRoute('app_login');
            }
            $article->setAuthor($user);

            // Gérer le téléchargement de l'image
            $imageFile = $form->get('imageFile')->getData();
            if ($imageFile) {
                $filename = uniqid() . '.' . $imageFile->guessExtension();
                $imageFile->move($this->getParameter('articles_directory'), $filename);
                $article->setImage('/articles/' . $filename);
            }

            // Créer une nouvelle instance de Stock
            $stock = new Stock();
            $stock->setNbrArticle($form->get('stock')->getData());
            $stock->setArticle($article);

            // Enregistrer l'article et le stock
            $entityManager->persist($article);
            $entityManager->persist($stock);
            $entityManager->flush();

            $this->addFlash('success', 'Article mis en vente avec succès.');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('articles/sell.html.twig', [
            'form' => $form->createView()
        ]);
    }
} 