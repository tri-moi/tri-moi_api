# Tri&Moi : API

## Installer le projet

Apres avoir cloné le projet, l'ouvrir dans un terminal puis installer les dépendances aves la commande `composer install`.

## Créer et installer la base de données

dans le terminal du projet, lancez les commandes `php bin/console doctrine:database:create`, `php bin/console make:migration
`, puis `php bin/console doctrine:migrations:migrate
`.
Enfin, lancez la commande `php bin/console doctrine:fixtures:load
`.

## Lancer le serveur

lancer `symfony serve` dans le terminal de votre projet.

### Pour les informations sur comment lancer le site web, voyez le readme dans son dossier
