
# Projet Symfony Site E-Commerce

## Description

Ce projet est un projet à titre éducatif dont le but est de créer un site web sur le thème de l'e-commerce en utilisant le langage de programmation PHP. \
Sur ce site, il est possible de se créer un compte, de se connecter et d'ajouter des articles dans son panier pour ensuite les acheter avec de l'argent fictif. \
Il est possible d'ajouter des articles en précisant sa description (taille, prix,nom, catégorie, image...). \
Il est également possible de supprimer les articles ajouter. \
Une page profil permet à l'utilisateur connecté de pouvoir modifier ses informations.

### Côté admin

L'administrateur détient un compte qui permet de visualiser l'ensemble des utilisateurs créer et à la possibilité d'en supprimer.



### Quelques commandes pour lancer correctement le projet :

```
composer install
```
Installe toutes les dépendances du projet définies dans le fichier composer.json

```
php bin/console doctrine:database:create
```
Crée la base de données définie dans ton fichier .env (ou .env.local).

```
php bin/console doctrine:schema:update --force
```
Met à jour la structure de la base de données pour qu'elle corresponde aux entités définies dans ton code.

```
php bin/console doctrine:migrations:migrate --no-interaction
```
Exécute les migrations pour synchroniser la base de données avec les entités du projet.

```
php bin/console doctrine:fixtures:load -n
```
Remplit la base de données avec des données de test (fixtures).

##

⚠️ Extension fileinfo dans php.ini 

Symfony utilise fileinfo pour gérer l'upload et le traitement des fichiers.

Si cette extension est désactivée, certaines fonctionnalités comme l'upload d'images ne fonctionneront pas.

Pour l'activer, trouve et décommente la ligne suivante dans le fichier php.ini (en retirant le ; devant) 