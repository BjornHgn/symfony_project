<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Article;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Créer un utilisateur
        $user = new User();
        $user->setEmail('admin@example.com')
            ->setUsername('admin')
            ->setRoles(['ROLE_ADMIN']);

        $hashedPassword = $this->passwordHasher->hashPassword($user, 'password123');
        $user->setPassword($hashedPassword);

        $manager->persist($user);

        $articles = [
            [
                'nom' => 'T-shirt Homme',
                'categorie' => 'Vêtements',
                'genre' => 'Homme',
                'prix' => 19.99,
                'description' => 'T-shirt basique en coton, confortable et élégant.',
                'image' => '/articles/t-shirtblanc.jpg'
            ],
            [
                'nom' => 'Robe Femme',
                'categorie' => 'Vêtements',
                'genre' => 'Femme',
                'prix' => 39.99,
                'description' => 'Robe élégante pour toutes les occasions.',
                'image' => '/articles/roberouge.jpg'
            ],
            [
                'nom' => 'Chaussures de sport Homme',
                'categorie' => 'Chaussures',
                'genre' => 'Homme',
                'prix' => 79.99,
                'description' => 'Chaussures de sport confortables pour une utilisation quotidienne.',
                'image' => '/articles/chaussurehommesport.jpg'
            ],
            [
                'nom' => 'Bottes Femme',
                'categorie' => 'Chaussures',
                'genre' => 'Femme',
                'prix' => 99.99,
                'description' => 'Bottes élégantes en cuir pour l\'hiver.',
                'image' => '/articles/bottefemme.jpg'
            ],
            [
                'nom' => 'Sac à main en cuir',
                'categorie' => 'Accessoires',
                'genre' => 'Femme',
                'prix' => 129.99,
                'description' => 'Sac à main en cuir véritable, parfait pour toutes les occasions.',
                'image' => '/articles/sacamaincuire.jpg'
            ],
            [
                'nom' => 'Montre Homme',
                'categorie' => 'Accessoires',
                'genre' => 'Homme',
                'prix' => 149.99,
                'description' => 'Montre élégante avec bracelet en cuir.',
                'image' => '/articles/montrehomme.jpg'
            ],
            [
                'nom' => 'Jean Slim Femme',
                'categorie' => 'Vêtements',
                'genre' => 'Femme',
                'prix' => 49.99,
                'description' => 'Jean slim confortable et stylé.',
                'image' => '/articles/jeansslimefemme.jpg'
            ],
            [
                'nom' => 'Blouson Homme',
                'categorie' => 'Vêtements',
                'genre' => 'Homme',
                'prix' => 89.99,
                'description' => 'Blouson en cuir pour un look moderne et audacieux.',
                'image' => '/articles/blousoncuirehomme.jpg'
            ],
            [
                'nom' => 'Pull Femme',
                'categorie' => 'Vêtements',
                'genre' => 'Femme',
                'prix' => 34.99,
                'description' => 'Pull en laine, idéal pour les journées fraîches.',
                'image' => '/articles/pulllainefemme.jpg'
            ],
            [
                'nom' => 'Pantalon Chino Homme',
                'categorie' => 'Vêtements',
                'genre' => 'Homme',
                'prix' => 59.99,
                'description' => 'Pantalon chino classique pour un look chic décontracté.',
                'image' => '/articles/pantalonchinohomme.jpg'
            ],
            [
                'nom' => 'Chaussures Baskets Femme',
                'categorie' => 'Chaussures',
                'genre' => 'Femme',
                'prix' => 59.99,
                'description' => 'Baskets blanches tendance, confort et style.',
                'image' => '/articles/basketfemmeblanche.jpg'
            ],
            [
                'nom' => 'Veste de costume Homme',
                'categorie' => 'Vêtements',
                'genre' => 'Homme',
                'prix' => 179.99,
                'description' => 'Veste de costume classique, parfait pour les occasions formelles.',
                'image' => '/articles/vestecostumehomme.jpg'
            ],
            [
                'nom' => 'Chapeau de paille Femme',
                'categorie' => 'Accessoires',
                'genre' => 'Femme',
                'prix' => 25.99,
                'description' => 'Chapeau de paille léger, idéal pour l\'été.',
                'image' => '/articles/chapeaupaillefemme.jpg'
            ],
            [
                'nom' => 'Cagoule Homme',
                'categorie' => 'Accessoires',
                'genre' => 'Homme',
                'prix' => 15.99,
                'description' => 'Cagoule chaude pour l\'hiver.',
                'image' => '/articles/cagoulehomme.jpg'
            ],
            [
                'nom' => 'Mocassins Femme',
                'categorie' => 'Chaussures',
                'genre' => 'Femme',
                'prix' => 69.99,
                'description' => 'Mocassins élégants en daim, parfaits pour une allure chic.',
                'image' => '/articles/mocassinfemme.jpg'
            ],
            [
                'nom' => 'T-shirt à imprimé Homme',
                'categorie' => 'Vêtements',
                'genre' => 'Homme',
                'prix' => 24.99,
                'description' => 'T-shirt avec un imprimé graphique moderne.',
                'image' => '/articles/imprimehomme.jpg'
            ],
            [
                'nom' => 'Blouse Femme',
                'categorie' => 'Vêtements',
                'genre' => 'Femme',
                'prix' => 39.99,
                'description' => 'Blouse légère en soie, idéale pour le printemps.',
                'image' => '/articles/blousefemme.jpg'
            ],
            [
                'nom' => 'Casquette Homme',
                'categorie' => 'Accessoires',
                'genre' => 'Homme',
                'prix' => 19.99,
                'description' => 'Casquette à la mode pour un look décontracté.',
                'image' => '/articles/casquettehomme.jpg'
            ],
            [
                'nom' => 'Gants en cuir Homme',
                'categorie' => 'Accessoires',
                'genre' => 'Homme',
                'prix' => 49.99,
                'description' => 'Gants en cuir de qualité pour l\'hiver.',
                'image' => '/articles/gantscuir.jpg'
            ],
            [
                'nom' => 'Chaussettes en laine',
                'categorie' => 'Accessoires',
                'genre' => 'Unisexe',
                'prix' => 9.99,
                'description' => 'Chaussettes douces en laine, parfaites pour l\'hiver.',
                'image' => '/articles/chaussettelaine.jpg'
            ],
            [
                'nom' => 'Gants en cuir Homme',
                'categorie' => 'Accessoires',
                'genre' => 'Homme',
                'prix' => 49.99,
                'description' => 'Gants en cuir de qualité pour l\'hiver.',
                'image' => '/articles/gantscuir.jpg'
            ],
            [
                'nom' => 'Gants en cuir Homme',
                'categorie' => 'Accessoires',
                'genre' => 'Homme',
                'prix' => 49.99,
                'description' => 'Gants en cuir de qualité pour l\'hiver.',
                'image' => '/articles/gantscuir.jpg'
            ],
            [
                'nom' => 'Gants en cuir Homme',
                'categorie' => 'Accessoires',
                'genre' => 'Homme',
                'prix' => 49.99,
                'description' => 'Gants en cuir de qualité pour l\'hiver.',
                'image' => '/articles/gantscuir.jpg'
            ],
            [
                'nom' => 'Gants en cuir Homme',
                'categorie' => 'Accessoires',
                'genre' => 'Homme',
                'prix' => 49.99,
                'description' => 'Gants en cuir de qualité pour l\'hiver.',
                'image' => '/articles/gantscuir.jpg'
            ]
        ];

        foreach ($articles as $data) {
            $article = new Article();
            $article->setNom($data['nom'])
                   ->setCategorie($data['categorie'])
                   ->setGenre($data['genre'])
                   ->setPrix($data['prix'])
                   ->setDescription($data['description'])
                   ->setImage($data['image'])
                   ->setCreatedAt(new \DateTime())
                   ->setAuthor($user);
            
            $manager->persist($article);
        }

        $manager->flush();
    }
}