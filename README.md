### Projet XYZ
### Prérequis

Pour utiliser ce projet, vous devez disposer d’un environnement Linux avec les éléments suivants installés :

    Docker

    Makefile (outil make)

Assurez-vous que Docker est correctement configuré et que vous pouvez exécuter des commandes Docker sans problème.

### Installation

1.  Cloner le dépôt:

```
https://github.com/MandfredGRONDIN/library-subject-bloc-3.git
cd library-subject-bloc-3
```

2.  Ajouter un fichier .env avec les coordonées de la base:

```
MYSQL_ROOT_PASSWORD=xxxx
MYSQL_DATABASE=xxxxx
MYSQL_USER=xxxxxx
MYSQL_PASSWORD=xxxxx

PHP_ENV=dev

```

3.  Construire et lancer les services avec:

```
make dev-detach
```

4.  Un utilisateur admin est créer automatiquement avec ces coordonnées :

```
'Nom: Admin', 'Prénom: Admin', 'Email: admin@admin.fr', 'Password: admin123', 'Role: admin'

Important : Pour des raisons de sécurité, pensez à modifier ce mot de passe directement via phpMyAdmin.
```

