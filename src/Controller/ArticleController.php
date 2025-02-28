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
        if ($request->isMethod('POST')) {
            // Récupérer les données du formulaire
            $name = $request->request->get('name');
            $description = $request->request->get('description');
            $price = $request->request->get('price');
            $category = $request->request->get('category');
            $genre = $request->request->get('genre');
            $imageFile = $request->files->get('image');
            $stockQuantity = $request->request->get('stock'); // Récupérer la quantité de stock

            // Créer une nouvelle instance de l'article
            $article = new Article();
            $article->setNom($name);
            $article->setDescription($description);
            $article->setPrix($price);
            $article->setCategorie($category);
            $article->setGenre($genre);
            $article->setCreatedAt(new \DateTime()); // Définir la date de création

            // Définir l'auteur (par exemple, l'utilisateur connecté)
            $user = $this->getUser(); // Récupérer l'utilisateur connecté
            if ($user) {
                $article->setAuthor($user); // Assurez-vous que l'utilisateur est défini
            } else {
                // Gérer le cas où l'utilisateur n'est pas connecté
                $this->addFlash('error', 'Vous devez être connecté pour vendre un article.');
                return $this->redirectToRoute('app_login'); // Redirige vers la page de connexion
            }

            // Gérer le téléchargement de l'image
            if ($imageFile) {
                // Générer un nom de fichier unique
                $filename = uniqid() . '.' . $imageFile->guessExtension();
                
                // Déplacer le fichier vers le répertoire public/articles
                $imageFile->move($this->getParameter('articles_directory'), $filename);
                
                // Enregistrer le chemin de l'image dans l'article
                $article->setImage('/articles/' . $filename); // Assurez-vous que votre entité Article a une méthode setImage()
            }

            // Créer une nouvelle instance de Stock
            $stock = new Stock();
            $stock->setNbrArticle($stockQuantity); // Définir la quantité de stock
            $stock->setArticle($article); // Lier le stock à l'article

            // Enregistrer l'article et le stock dans la base de données
            $entityManager->persist($article);
            $entityManager->persist($stock);
            $entityManager->flush();

            $this->addFlash('success', 'Article mis en vente avec succès.');
            return $this->redirectToRoute('app_home'); // Redirige vers la page d'accueil ou une autre page
        }

        return $this->render('articles/sell.html.twig');
    }
} 