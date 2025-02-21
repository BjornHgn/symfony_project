<?php
namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class ArticlesController extends AbstractController
{
    #[Route('/articles', name: 'app_articles')]
    public function index(ArticleRepository $articleRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $queryBuilder = $articleRepository->createQueryBuilder('a');

        $pagination = $paginator->paginate(
            $queryBuilder, 
            $request->query->getInt('page', 1), 
            10 
        );

        return $this->render('articles/index.html.twig', [
            'articles' => $pagination,
        ]);
    }

    #[Route('/articles/{genre}/{categorie}', name: 'app_articles_category')]
    public function category(string $genre, string $categorie, ArticleRepository $articleRepository, Request $request, PaginatorInterface $paginator): Response
    {
        if ($categorie === 'Tous') {
            $queryBuilder = $articleRepository->createQueryBuilder('a')
                ->where('a.genre = :genre')
                ->setParameter('genre', $genre);
        } else {
            $queryBuilder = $articleRepository->createQueryBuilder('a')
                ->where('a.genre = :genre')
                ->andWhere('a.categorie = :categorie')
                ->setParameter('genre', $genre)
                ->setParameter('categorie', $categorie);
        }

        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1), 
            10 
        );

        return $this->render('articles/category.html.twig', [
            'articles' => $pagination,
            'genre' => $genre,
            'categorie' => $categorie,
        ]);
    }

    #[Route('/articles/{genre}', name: 'app_articles_genre')]
    public function genre(string $genre, ArticleRepository $articleRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $queryBuilder = $articleRepository->createQueryBuilder('a')
            ->where('a.genre = :genre')
            ->setParameter('genre', $genre);

        $pagination = $paginator->paginate(
            $queryBuilder, 
            $request->query->getInt('page', 1), 
            10 
        );

        return $this->render('articles/category.html.twig', [
            'articles' => $pagination,
            'genre' => $genre,
            'categorie' => 'Tous',
        ]);
    }
    #[Route('/article/{id}', name: 'app_article_details')]
public function details(int $id, ArticleRepository $articleRepository): Response
{
    $article = $articleRepository->find($id);

    if (!$article) {
        throw $this->createNotFoundException('Article non trouvé');
    }

    return $this->render('articles/details.html.twig', [
        'article' => $article,
    ]);
}

    
 
}
