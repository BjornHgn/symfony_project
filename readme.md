
# Projet Symfony Site E-Commerce

## Description

Ce projet est un projet à titre éducatif dont le but est de créer un site web sur le thème de l'e-commerce en utilisant le langage de programmation PHP.
Sur ce site, il est possible de se créer un compte, de se connecter et d'ajouter des articles dans son panier pour ensuite les acheter avec de l'argent fictif.
Il est possible d'ajouter des articles en précisant sa description (taille, prix,nom, catégorie, image...).
Il est également possible de supprimer les articles ajouter.
Une page profil permet à l'utilisateur connecté de pouvoir modifier ses informations.

### Côté admin

L'administrateur détient un compte qui permet de visualiser l'ensemble des utilisateurs créer et à la possibilité d'en supprimer.



### Quelques commandes pour lancer correctement le projet

composer install
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console doctrine:fixtures:load -n


### 

extension a enlever  ; fileinfo dans php.ini