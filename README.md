# Test Kalitics fullstack

## Prérequis
* Installer le CLI de symfony
* Préparer une base de donnée SQl (le projet a été créé avec mariaDB)

## Commandes à exécuter
```shell
# Adapter DATABASE_URL dans .env pour votre database SQL
symfony composer install
symfony console doctrine:migrations:migrate

# Pour démarrer le serveur local
symfony serve
```
Informations sur mes démarches et les outils utilisés :

Je me suis concentré pendant trois jours pour comprendre Symfony dans sa globalité. J'ai suivi de nombreux tutoriels ainsi que la documentation officielle de Symfony. J'ai également utilisé ChatGPT lorsque j'ai rencontré un problème concernant la collecte des données dans le fichier Clocking. En particulier, j'avais besoin de sélectionner plusieurs collaborateurs afin de les enregistrer dans ma base de données lorsque le chef de projet souhaite pointer plusieurs collaborateurs le même jour sur le même chantier sans remplir le formulaire de création autant de fois que le nombre de collaborateurs à pointer.

J'ai apporté beaucoup de mises en forme sur l'application, notamment sur le menu, les formulaires de saisie, et une petite alerte lorsque le collaborateur veut remplir plusieurs fois le même formulaire de création de pointage juste pour modifier le chantier et la durée, étant donné qu’il s’agit du même jour et du même collaborateur.

