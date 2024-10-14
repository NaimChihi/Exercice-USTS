### Contexte du projet :

Vous devez développer une application de **gestion de projets multi-sociétés** dans laquelle un utilisateur peut être associé à plusieurs sociétés, avec des **droits spécifiques** pour chaque société. Les utilisateurs peuvent consulter et gérer des projets au sein des sociétés auxquelles ils appartiennent, en fonction de leurs rôles.

Le projet vous sera fourni avec une configuration de base incluant [Symfony](https://symfony.com/doc/current/index.html), [API Platform](https://api-platform.com/docs/core/) et une authentification [JWT](https://github.com/lexik/LexikJWTAuthenticationBundle).

---

### Installation

Si vous décidez de fork ce repo, il est préférable d'en faire une copie privée et de nous en donner les droits de lecture une fois l'exercice terminé.

#### Démarrage Docker

```shell
docker compose build --no-cache
docker compose up --pull always --wait
```

Se référer à la [documentation relative à Docker](https://github.com/dunglas/symfony-docker)

#### Génération des clefs JWT

```shell
docker compose exec php bin/console lexik:jwt:generate-keypair
```

#### Chargement du jeu de tests

```shell
docker compose exec php bin/console doctrine:fixtures:load --no-interaction
```

#### Vérifications

Accéder à https://localhost/api et vérifier que la documentation API se charge bien.
Utiliser votre client HTTP préféré afin de tester une connexion à l'API sur la route d'authentication.
Utilisateurs de test : `user1@local.host:my_password` ou `user2@local.host:my_password`

---

### Objectif :

L'objectif est de développer une API REST qui permet aux utilisateurs authentifiés de gérer des sociétés et des projets au sein de ces sociétés, tout en respectant les droits d'accès définis par leurs rôles.

---

### Spécifications fonctionnelles :

1. **Gestion des sociétés** :

    - Une société est composée de :
        - Nom
        - Numéro SIRET
        - Adresse
    - Un utilisateur peut appartenir à plusieurs sociétés, avec des rôles différents selon la société.
    - Chaque société peut avoir plusieurs projets.
    - Vous devez permettre aux utilisateurs de :
        - Récupérer la liste des sociétés auxquelles ils appartiennent.
        - Accéder aux détails d'une société spécifique.

2. **Gestion des projets** :

    - Un projet est composé de :
        - Titre
        - Description
        - Date de création
    - Chaque société peut avoir plusieurs projets.
    - Vous devez permettre aux utilisateurs de :
        - Créer un projet au sein d'une société si leur rôle le permet.
        - Récupérer la liste des projets d'une société à laquelle ils appartiennent.
        - Modifier et supprimer des projets si leur rôle le permet.
        - Consulter les détails d’un projet spécifique au sein d'une société.

3. **Gestion des droits** :

    - Les droits des utilisateurs sont définis **par société**. Un utilisateur peut avoir des rôles différents dans chaque société : `admin`, `manager`, `consultant`
    - Un utilisateur ne peut avoir qu'un seul rôle par société
    - Les actions que peut effectuer un utilisateur sur les projets ou sociétés dépendent de son rôle dans la société correspondante. Voici les restrictions principales :
        - Seul un utilisateur ayant le rôle `admin` peut ajouter un utilisateur à une société
        - Seul un utilisateur ayant le rôle `admin` ou `manager` peut créer, modifier, ou supprimer des projets dans une société.
        - Un utilisateur avec le rôle `consultant` ne peut que consulter les projets d'une société.
        - Un utilisateur doit être membre d'une société pour accéder aux projets et données de cette société.

4. **Tests et validations** :
    - Vous devez écrire des tests (unitaires et/ou fonctionnels) pour valider les fonctionnalités suivantes :
        - L’accès aux sociétés et projets par les utilisateurs, en fonction de leurs rôles.
        - La création, modification et suppression de projets, avec des contrôles de droits.
        - La restriction de l'accès aux sociétés et projets pour les utilisateurs non membres.

**Notes :**
Les utilisateurs étant déjà fournis, il n'est pas nécessaire d'en sécuriser les routes (même si conseillé). En revanche, vous devez les configurer afin de les rendre accessible en tant que ressource API.

---

### Résultats attendus :

-   **API REST fonctionnelle** : Vous devez fournir une API utilisant **API Platform**, qui permet de gérer les sociétés, les projets et les droits des utilisateurs sur ces entités.
-   **Sécurité des accès** : Les accès aux différentes ressources doivent être sécurisés et respecter les rôles des utilisateurs dans chaque société.
-   **Tests** : Des tests complets doivent valider les règles de gestion des droits et les fonctionnalités principales.

---

### Critères d'évaluation :

-   **Respect des spécifications** : L'API doit permettre aux utilisateurs d’interagir avec les sociétés et les projets selon leurs droits, en respectant les rôles assignés.
-   **Gestion des droits** : La sécurité et la gestion des droits d'accès doivent être correctement implémentées.
-   **Qualité du code** : Votre code doit être bien structuré, maintenable, et suivre les bonnes pratiques de développement Symfony.
-   **Tests** : Les tests doivent couvrir les cas d’usage et valider les restrictions d’accès.

---

Cet exercice vous permet de démontrer vos compétences en gestion de droits d'accès dans une application multi-sociétés, ainsi que votre capacité à organiser et sécuriser une API Symfony avec des cas d'usage complexes.

---------------------------------------------------------------------------------------------------------------- ## Résumé du développement pour l'exercice USTS ##

## Étapes du Développement :

-   **Configuration de l'Environnement** :

J'ai commencé par configurer l'environnement de développement en utilisant Docker, ce qui m'a permis de gérer facilement les dépendances et de garantir la cohérence de l'application sur différentes machines.
J'ai créé un repository Github pour pouvoir sauvegarder mon travail régulièrement.

-   **Mise en Place de la Base de Données** :

J'ai conçu le schéma de la base de données pour gérer les sociétés et les projets, en veillant à respecter les relations entre les entités. J'ai également utilisé Doctrine pour interagir avec la base de données PostgreSQL.
J'ai utilisé pgAdmin pour l'interface de ma base de données.

-   **Développement des Fonctions API** :

J'ai créé des endpoints RESTful pour permettre aux utilisateurs de créer, lire, mettre à jour et supprimer des sociétés et des projets. Chaque endpoint a été sécurisé en fonction des rôles des utilisateurs (admin, manager, consultant), garantissant ainsi que seules les actions autorisées soient effectuées.

-   **Gestion des Droits d'Accès** :

J'ai mis en place un système de contrôle d'accès qui restreint les actions des utilisateurs en fonction de leur rôle dans chaque société. Cela a inclus des vérifications dans les contrôleurs pour s'assurer que les utilisateurs ne peuvent accéder qu'aux ressources pertinentes.

-   **Tests et Validation** :

J'ai écrit des tests unitaires et fonctionnels pour valider le bon fonctionnement de l'API et des droits d'accès. Cela a permis d'identifier et de corriger certains problèmes avant le déploiement.
Lors de la réalisation des tests, j'ai rencontré des difficultés, notamment avec le ProjectControllerTest, où j'ai dû résoudre des problèmes liés à la gestion des exceptions.

-   **Documentation** :

J'ai documenté l'API et les fonctionnalités clés pour faciliter l'utilisation par d'autres développeurs et pour les tests futurs.
