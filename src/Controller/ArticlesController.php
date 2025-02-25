<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\StockRepository;

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
    public function details(Article $article, StockRepository $stockRepository): Response
    {
        $stock = $stockRepository->findOneBy(['article' => $article]);
        
        return $this->render('articles/details.html.twig', [
            'article' => $article,
            'stockQuantity' => $stock ? $stock->getNbrArticle() : 0
        ]);
    }

    #[Route('/article/edit/{id}', name: 'app_article_edit')]
    public function editArticle(int $id, Request $request, ArticleRepository $articleRepository, EntityManagerInterface $entityManager): Response
    {
        $article = $articleRepository->find($id);

        if (!$article) {
            throw $this->createNotFoundException('Article not found');
        }

        $user = $this->getUser();
        if ($article->getAuthor() !== $user && !$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('You do not have permission to edit this article.');
        }

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_article_details', ['id' => $article->getId()]);
        }

        return $this->render('articles/edit.html.twig', [
            'form' => $form->createView(),
            'article' => $article,
        ]);
    }

    #[Route('/article/delete/{id}', name: 'app_article_delete', methods: ['POST'])]
    public function deleteArticle(int $id, ArticleRepository $articleRepository, EntityManagerInterface $entityManager): Response
    {
        $article = $articleRepository->find($id);

        if (!$article) {
            throw $this->createNotFoundException('Article not found');
        }

        $user = $this->getUser();
        if ($article->getAuthor() !== $user && !$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('You do not have permission to delete this article.');
        }

        $entityManager->remove($article);
        $entityManager->flush();

        return $this->redirectToRoute('app_articles');
    }
}