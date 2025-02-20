<?php
// src/Controller/ArticlesController.php
namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticlesController extends AbstractController
{
    #[Route('/articles', name: 'app_articles')]
    public function index(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findAll();

        return $this->render('articles/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/articles/{genre}/{categorie}', name: 'app_articles_category')]
    public function category(string $genre, string $categorie, ArticleRepository $articleRepository): Response
    {
        if ($categorie === 'Tous') {
            $articles = $articleRepository->findBy([
                'genre' => $genre,
            ]);
        } else {
            $articles = $articleRepository->findBy([
                'genre' => $genre,
                'categorie' => $categorie,
            ]);
        }

        return $this->render('articles/category.html.twig', [
            'articles' => $articles,
            'genre' => $genre,
            'categorie' => $categorie,
        ]);
    }

    #[Route('/articles/{genre}', name: 'app_articles_genre')]
    public function genre(string $genre, ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findBy([
            'genre' => $genre,
        ]);

        return $this->render('articles/category.html.twig', [
            'articles' => $articles,
            'genre' => $genre,
            'categorie' => 'Tous', 
        ]);
    }
}
