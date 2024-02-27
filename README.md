
# <p align="center">Site pour une artiste</p>
  
## 📑 Présentation 

**Objectif**

L'objectif principal de SiteManon est de fournir un espace qui sert non seulement de reflet à l'identité artistique de Manon mais aussi comme un outil puissant pour augmenter sa visibilité et renforcer sa réputation dans le milieu artistique. Nous cherchons à créer une connexion directe entre l'artiste et son public, facilitant ainsi l'interaction, la découverte et l'appréciation de son œuvre.

**Public Cible**

SiteManon est conçu pour attirer une audience variée, incluant mais ne se limitant pas à :

- Galeries d'art
- Collectionneurs
- Autres artistes
- Amateurs d'art contemporain
- Notre ambition est de répondre aux besoins et attentes de ce public diversifié, tout en aidant l'artiste à établir des liens professionnels solides et à atteindre un public plus large.

**Caractéristiques**

- Galerie d'Œuvres : Explorez les créations de Manon à travers une interface visuellement attrayante et facile à naviguer.
- Profil de l'Artiste : Découvrez l'histoire, la vision et le parcours artistique de Manon.
- Contact Direct : Une passerelle directe pour communiquer avec l'artiste, que ce soit pour des collaborations, des expositions ou des acquisitions.
- Événements et Expositions : Restez informé des dernières expositions, événements et ateliers impliquant l'artiste.
    
## 🧐 Features    

- OnePage
- Blogw
- Forum
- Formulaire de contact
- E-commerce
        
## 🛠️ Tech Stack
- JavaScript
- PHP / SYMFONY
- Twig
- CSS
    
## 🛠️ Install Dependencies    
Ce guide détaille les étapes nécessaires pour télécharger et installer le projet SiteManon sur votre machine locale. Le projet est basé sur le framework Symfony, un framework PHP pour la création d'applications web.

**Prérequis**

Avant de commencer, assurez-vous que les éléments suivants sont installés sur votre système :

- PHP (version recommandée correspondant au projet, par exemple, 7.4 ou plus récent)
- Composer : le gestionnaire de dépendances PHP
- Symfony CLI : pour faciliter le développement avec le framework Symfony
- Serveur MySQL ou PostgreSQL : pour la gestion de la base de données

**Étape 1 : Cloner le projet**

Ouvrez un terminal et exécutez la commande suivante pour cloner le dépôt Git du projet dans le répertoire de votre choix :

```bash
git clone https://github.com/AnotheerDev/SiteManon.git
```

Cette commande télécharge le code source du projet sur votre machine.

**Étape 2 : Installer les dépendances**

Naviguez dans le répertoire du projet cloné :

```bash
cd SiteManon
```

Ensuite, utilisez Composer pour installer les dépendances du projet :

```bash
composer install
```

Cette commande lit le fichier composer.json à la racine du projet et télécharge toutes les dépendances nécessaires.

**Étape 3 : Configurer l'environnement**

Dupliquez le fichier .env ou .env.example à la racine du projet en un nouveau fichier nommé .env.local. Ouvrez ce fichier et configurez les paramètres de connexion à la base de données selon votre environnement de développement local :

```bash
# Exemple de configuration de la base de données
DATABASE_URL="mysql://username:password@localhost:3306/nom_de_la_base_de_donnees"
```

**Étape 4 : Créer la base de données**

Si votre base de données n'est pas encore créée, exécutez la commande suivante :

```bash
php bin/console doctrine:database:create
```

**Étape 5 : Exécuter les migrations**

Pour créer ou mettre à jour les tables dans la base de données, exécutez les migrations :

```bash
php bin/console doctrine:migrations:migrate
```

**Étape 6 : Lancer le serveur de développement**

Utilisez la CLI Symfony pour démarrer le serveur de développement :

```bash
symfony server:start
```

Ouvrez un navigateur et accédez à l'adresse indiquée par la commande (par défaut : http://127.0.0.1:8000) pour voir le projet en action.
        

## 📫 Contribution
Nous accueillons les contributions de la communauté ! Si vous avez des suggestions ou souhaitez contribuer au projet, n'hésitez pas à soumettre une issue ou une pull request.
