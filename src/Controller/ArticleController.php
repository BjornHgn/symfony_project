<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\StockRepository;

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
} 