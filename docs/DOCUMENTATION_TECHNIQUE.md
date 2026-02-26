# Documentation Technique — CMS Éditorial

## Table des matières

1. [Stack technologique](#1-stack-technologique)
2. [Architecture du projet](#2-architecture-du-projet)
3. [Framework maison](#3-framework-maison)
4. [ORM par annotations PHP 8](#4-orm-par-annotations-php-8)
5. [Schéma de base de données](#5-schéma-de-base-de-données)
6. [API REST — Référence complète](#6-api-rest--référence-complète)
7. [Interface d'administration (HTML rendu côté serveur)](#7-interface-dadministration-html-rendu-côté-serveur)
8. [Frontend public (Vite multi-pages)](#8-frontend-public-vite-multi-pages)
9. [Sécurité](#9-sécurité)
10. [Infrastructure Docker](#10-infrastructure-docker)
11. [Guide d'installation](#11-guide-dinstallation)

---

## 1. Stack technologique

| Couche | Technologie | Version | Rôle |
|--------|------------|---------|------|
| Langage backend | PHP | 8.4 | Logique métier, rendu vues, API REST |
| Serveur HTTP | Apache | 2.4 | Reverse proxy, mod_rewrite |
| Base de données | PostgreSQL | 16 | Persistance relationnelle |
| Langage frontend | JavaScript (ESModules) | ES2022 | Interactions côté client |
| Bundler frontend | Vite | 5.x | Build multi-pages, dev server HMR |
| CSS preprocessing | SASS (SCSS) | — | Styles modulaires |
| Normalisation CSS | modern-normalize | 3.x | Reset CSS cross-browser cohérent |
| Conteneurisation | Docker + Docker Compose | — | Isolation des services |
| Autoloading PHP | PSR-4 (Composer autoload) | — | `App\\` → `src/` |

> **Contrainte académique :** aucune dépendance Composer tierce (`"require": {}`) — le framework, le router, l'ORM et le système de sécurité ont tous été développés manuellement.

---

## 2. Architecture du projet

```
projet-semestriel/
├── api/                          # Backend PHP (container Docker)
│   ├── app/
│   │   ├── bin/console.php       # Point d'entrée CLI
│   │   ├── config/
│   │   │   ├── routes.json       # Définition de toutes les routes
│   │   │   └── database.json     # Configuration de connexion BDD
│   │   ├── src/
│   │   │   ├── index.php         # Point d'entrée HTTP 
│   │   │   ├── Controllers/
│   │   │   │   ├── Admin/        # Contrôleurs interface d'administration
│   │   │   │   ├── Api/v1/       # Contrôleurs API REST JSON
│   │   │   │   ├── Auth/         # Contrôleurs authentification
│   │   │   │   ├── Public/       # Contrôleurs frontend public
│   │   │   │   └── Errors/       # Pages d'erreur (403, 404)
│   │   │   ├── Entities/         # Classes entités (Article, User, Category…)
│   │   │   ├── Repositories/     # Couche accès données (PDO)
│   │   │   └── Lib/
│   │   │       ├── Http/         # Router, Request, Response, Middleware
│   │   │       ├── Controllers/  # AbstractController
│   │   │       ├── Repositories/ # AbstractRepository (ORM)
│   │   │       ├── Database/     # DatabaseConnexion, Dsn
│   │   │       ├── Auth/         # Session, CsrfToken, PermissionService
│   │   │       └── Annotations/  # Attributs PHP 8 (ORM/, AbstractAnnotation)
│   │   ├── vendor/               # Autoloader Composer (aucune dépendance tierce)
│   │   ├── views/                # Templates HTML (admin/, auth/, components/)
│   │   └── uploads/              # Fichiers uploadés
│   ├── database/
│   │   └── init/                 # Scripts SQL d'initialisation (01 → 08)
│   ├── docker-compose.yml
│   └── Dockerfile
├── frontend/                     # Frontend public (Vite)
│   ├── index.html                # Page d'accueil
│   ├── pages/                    # Pages HTML multi-entrées
│   │   ├── article.html
│   │   ├── category.html
│   │   ├── tag.html
│   │   ├── search.html
│   │   └── legal.html
│   ├── src/
│   │   ├── main.js               # Point d'entrée principal
│   │   ├── css/                  # Styles SCSS
│   │   └── js/
│   │       ├── components/       # Composants JS réutilisables
│   │       └── services/api.js   # Client API REST
│   └── vite.config.js
└── package.json                  # Scripts racine
```

### Patron architectural

Le projet suit le patron **MVC (Modèle-Vue-Contrôleur)** :

- **Modèle** : `Entities/` (structure des données) + `Repositories/` (accès base de données)
- **Vue** : `views/*.html` pour l'admin, templates HTML servis par Vite pour le frontend public
- **Contrôleur** : `Controllers/` — un contrôleur par action (principe de responsabilité unique)

---

## 3. Framework maison

### 3.1 Point d'entrée HTTP — `index.php`

Toutes les requêtes HTTP arrivent sur `index.php` via une règle Apache. Ce fichier :
1. Démarre l'autoloading PSR-4 via `vendor/autoload.php`
2. Instancie un objet `Request` (méthode, URI, corps, headers)
3. Passe la requête au `Router`
4. Le `Router` renvoie une `Response` qui est ensuite émise au client

### 3.2 Router — `Lib/Http/Router.php`

```
routes.json ---> Router::match(method, uri) ---> Controller::process(Request) ---> Response
```

- Lit l'intégralité de `routes.json` à chaque requête
- Compare la méthode HTTP et l'URI en supportant les **paramètres dynamiques** (`:id`, `:slug`) via regex
- Supporte le **`_method` override** dans les formulaires HTML POST (pour simuler `PATCH` et `DELETE`)
- Instancie le contrôleur par son namespace complet via `new $controllerClass()`
- Appelle `->process($request)` et émet la `Response`

### 3.3 Request & Response — `Lib/Http/`

- `Request` : encapsule méthode, URI, paramètres de route, corps JSON/form, headers, fichiers uploadés
- `Response` : code HTTP, headers, corps. Méthode `setContent()`, `addHeader()`, `setStatus()`
- `Response::json()` : raccourci pour les réponses API (Content-Type application/json)

### 3.4 AbstractController — `Lib/Controllers/AbstractController.php`

Classe abstraite dont héritent tous les contrôleurs. Fournit :

| Méthode | Description |
|---------|-------------|
| `process(Request): Response` | Méthode abstraite à implémenter dans chaque contrôleur |
| `render(string $template, array $data): Response` | Rendu d'un template HTML via `ob_start` / `require_once` + `extract($data)` |
| `requireRoles(array $roles): void` | Vérifie le rôle de l'utilisateur en session ; redirige vers `/login` ou `/403` si non autorisé |
| `requireCanManageUsers()` | Raccourci : admin uniquement |
| `requireCanManageCategories()` | Raccourci : admin + editor |
| `requireCanManageTags()` | Raccourci : admin + editor |
| `requireCanManageAllArticles()` | Raccourci : admin + editor |
| `requireCanPublishArticles()` | Raccourci : admin + editor |
| `canManageUsers(): bool` | Vérification sans redirection (pour les vues conditionnelles) |

---

## 4. ORM par annotations PHP 8

L'ORM maison utilise les **attributs PHP 8 natifs** (`#[Attribute]`) pour mapper les propriétés des entités aux colonnes SQL.

### 4.1 Attributs disponibles

| Attribut | Fichier | Rôle |
|----------|---------|------|
| `#[ORM]` | `ORM.php` | Marque une classe comme entité mappée |
| `#[Id]` | `Id.php` | Désigne la clé primaire |
| `#[AutoIncrement]` | `AutoIncrement.php` | Indique une colonne auto-incrémentée (SERIAL) |
| `#[Column(type, size?)]` | `Column.php` | Mappe une propriété à une colonne SQL |
| `#[References]` | `References.php` | Indique une clé étrangère |

### 4.2 Exemple d'entité

```php
#[ORM]
class Article extends AbstractEntity {
    #[Id]
    #[AutoIncrement]
    #[Column(type: 'int')]
    public int $id;

    #[Column(type: 'varchar', size: 255)]
    public string $title;

    #[Column(type: 'varchar', size: 50)]
    public string $status = 'draft';

    #[Column(type: 'int')]
    public int $author_id;
}
```

### 4.3 AbstractRepository — `Lib/Repositories/AbstractRepository.php`

- Connexion PDO via `DatabaseConnexion` (injection par `Dsn`)
- Lit `config/database.json` pour les paramètres de connexion
- Utilise la **Reflection PHP** pour construire dynamiquement les requêtes SQL à partir des annotations
- `getTable()` : déduit le nom de la table depuis le nom de la classe Repository (ex. `ArticleRepository` → `articles`)
- Méthodes génériques : `findAll()`, `findBy(conditions)`, `findOneBy(conditions)`, `save(entity)`, `update(entity)`, `delete(id)`

**Opérateurs de condition supportés :**

| Opérateur | Signification |
|-----------|---------------|
| `eq` | Égalité (`=`) |
| `neq` | Inégalité (`!=`) |
| `lt` | Inférieur strict (`<`) |
| `lte` | Inférieur ou égal (`<=`) |
| `gt` | Supérieur strict (`>`) |
| `gte` | Supérieur ou égal (`>=`) |
| `like` | Correspondance partielle (`LIKE '%…%'`) |
| `in` | Dans une liste de valeurs (`IN (…)`) |

---

## 5. Schéma de base de données

### 5.1 Vue d'ensemble — 9 tables

```
users <-------- articles ----> article_categories <-------- category
                          │                                              
                          ├--------> article_tags <-------- tags
                          │                                              
                          ├--------> article_versions         
                          │                                              
                          └--------> article_media <-------- media
```

### 5.2 Détail des tables

#### Table `users`
| Colonne | Type | Contrainte |
|---------|------|-----------|
| `id` | SERIAL | PK |
| `email` | VARCHAR(255) | UNIQUE NOT NULL |
| `password` | VARCHAR(255) | NOT NULL (hash bcrypt) |
| `firstname` | VARCHAR(255) | NOT NULL |
| `lastname` | VARCHAR(255) | NOT NULL |
| `role` | ENUM(`admin`, `editor`, `author`) | DEFAULT `author` |
| `created_at` | TIMESTAMP | DEFAULT NOW() |
| `updated_at` | TIMESTAMP | AUTO (trigger) |

#### Table `articles`
| Colonne | Type | Contrainte |
|---------|------|-----------|
| `id` | SERIAL | PK |
| `title` | VARCHAR(255) | NOT NULL |
| `slug` | VARCHAR(255) | UNIQUE NOT NULL — index |
| `content` | TEXT | — |
| `excerpt` | TEXT | — |
| `status` | ENUM(`draft`, `published`, `archived`) | DEFAULT `draft` — index |
| `author_id` | INTEGER | FK → `users(id)` ON DELETE CASCADE — index |
| `created_at` | TIMESTAMP | DEFAULT NOW() |
| `updated_at` | TIMESTAMP | AUTO (trigger) |
| `published_at` | TIMESTAMP | NULL jusqu'à publication |

#### Table `category`
| Colonne | Type | Contrainte |
|---------|------|-----------|
| `id` | SERIAL | PK |
| `name` | VARCHAR(255) | UNIQUE NOT NULL |
| `slug` | VARCHAR(255) | UNIQUE NOT NULL |
| `description` | TEXT | — |
| `created_at` | TIMESTAMP | DEFAULT NOW() |
| `updated_at` | TIMESTAMP | AUTO (trigger) |

#### Table `tags`
Structure identique à `category` (id, name, slug, description, created_at, updated_at).

#### Table `article_categories`
| Colonne | Type | Contrainte |
|---------|------|-----------|
| `article_id` | INTEGER | FK → `articles(id)` ON DELETE CASCADE |
| `category_id` | INTEGER | FK → `category(id)` ON DELETE CASCADE |
| — | — | PK composite (article_id, category_id) |

#### Table `article_tags`
Structure identique à `article_categories` (article_id, tag_id).

#### Table `article_versions`
| Colonne | Type | Contrainte |
|---------|------|-----------|
| `id` | SERIAL | PK |
| `article_id` | INTEGER | FK → `articles(id)` ON DELETE CASCADE |
| `title` | TEXT | NOT NULL (snapshot au moment de la sauvegarde) |
| `content` | TEXT | NOT NULL (snapshot au moment de la sauvegarde) |
| `author_id` | INTEGER | — |
| `created_at` | TIMESTAMP | DEFAULT NOW() |
| `updated_at` | TIMESTAMP | — |

#### Table `media`
| Colonne | Type | Contrainte |
|---------|------|-----------|
| `id` | SERIAL | PK |
| `filename` | VARCHAR | NOT NULL |
| `file_path` | VARCHAR | NOT NULL |
| `file_type` | VARCHAR | NOT NULL (image, video, audio, pdf) |
| `mime_type` | VARCHAR | NOT NULL |
| `file_size` | INTEGER | NOT NULL (octets) |
| `alt_text` | TEXT | — |
| `title` | VARCHAR | — |
| `description` | TEXT | — |
| `uploaded_by` | INTEGER | FK → `users(id)` |
| `created_at` | TIMESTAMP | DEFAULT NOW() |

#### Table `article_media`
| Colonne | Type | Contrainte |
|---------|------|-----------|
| `article_id` | INTEGER | FK → `articles(id)` ON DELETE CASCADE |
| `media_id` | INTEGER | FK → `media(id)` ON DELETE CASCADE |
| `is_featured` | BOOLEAN | DEFAULT FALSE |
| `display_order` | INTEGER | DEFAULT 0 |
| — | — | PK composite (article_id, media_id) |

### 5.3 Types ENUM PostgreSQL

```sql
CREATE TYPE user_role AS ENUM ('admin', 'editor', 'author');
CREATE TYPE article_status AS ENUM ('draft', 'published', 'archived');
```

---

## 6. API REST — Référence complète

Toutes les réponses sont en JSON. Base URL : `http://localhost:8079`

### 6.1 Authentification

| Méthode | Endpoint | Auth | Description |
|---------|----------|------|-------------|
| `POST` | `/api/v1/auth/login` | Non | Connexion, crée la session |
| `GET` | `/api/v1/auth/logout` | Oui | Déconnexion |
| `POST` | `/api/v1/auth/logout` | Oui | Déconnexion (formulaire) |
| `GET` | `/api/v1/auth/profile` | Oui | Profil de l'utilisateur connecté |

### 6.2 Articles

| Méthode | Endpoint | Auth | Description |
|---------|----------|------|-------------|
| `GET` | `/api/v1/articles` | Non | Liste des articles publiés |
| `GET` | `/api/v1/articles/:id` | Non | Un article par ID |
| `GET` | `/api/v1/articles/slug/:slug` | Non | Un article par slug |
| `GET` | `/api/v1/articles/:id/categories` | Non | Catégories d'un article |
| `GET` | `/api/v1/articles/:id/tags` | Non | Tags d'un article |
| `GET` | `/api/v1/articles/:id/versions` | Oui | Historique des versions |
| `GET` | `/api/v1/articles/:id/versions/:versionId` | Oui | Une version spécifique |
| `POST` | `/api/v1/articles` | Oui (author+) | Créer un article |
| `PATCH` | `/api/v1/articles/:id` | Oui (author+) | Modifier un article |
| `PATCH` | `/api/v1/articles/:id/publish` | Oui (editor+) | Publier un article |
| `PATCH` | `/api/v1/articles/:id/archive` | Oui (editor+) | Archiver un article |
| `DELETE` | `/api/v1/articles/:id` | Oui (editor+) | Supprimer un article |

### 6.3 Catégories

| Méthode | Endpoint | Auth | Description |
|---------|----------|------|-------------|
| `GET` | `/api/v1/categories` | Non | Liste des catégories |
| `GET` | `/api/v1/categories/:id` | Non | Une catégorie par ID |
| `GET` | `/api/v1/categories/:id/articles` | Non | Articles d'une catégorie |
| `POST` | `/api/v1/categories` | Oui (editor+) | Créer une catégorie |
| `PATCH` | `/api/v1/categories/:id` | Oui (editor+) | Modifier une catégorie |
| `DELETE` | `/api/v1/categories/:id` | Oui (editor+) | Supprimer une catégorie |

### 6.4 Tags

| Méthode | Endpoint | Auth | Description |
|---------|----------|------|-------------|
| `GET` | `/api/v1/tags` | Non | Liste des tags |
| `GET` | `/api/v1/tags/:id` | Non | Un tag par ID |
| `GET` | `/api/v1/tags/:id/articles` | Non | Articles d'un tag |
| `POST` | `/api/v1/tags` | Oui (editor+) | Créer un tag |
| `PATCH` | `/api/v1/tags/:id` | Oui (editor+) | Modifier un tag |
| `DELETE` | `/api/v1/tags/:id` | Oui (editor+) | Supprimer un tag |

### 6.5 Utilisateurs

| Méthode | Endpoint | Auth | Description |
|---------|----------|------|-------------|
| `GET` | `/api/v1/users` | Oui (admin) | Liste des utilisateurs |
| `GET` | `/api/v1/users/:id` | Oui (admin) | Un utilisateur par ID |
| `POST` | `/api/v1/users` | Oui (admin) | Créer un utilisateur |
| `PATCH` | `/api/v1/users/:id` | Oui (admin) | Modifier un utilisateur |
| `DELETE` | `/api/v1/users/:id` | Oui (admin) | Supprimer un utilisateur |

---

## 7. Interface d'administration (HTML rendu côté serveur)

L'interface d'administration est servie directement par PHP (rendu côté serveur), accessible sur le même domaine/port que l'API (port 8079).

### Routes d'administration

| Méthode | Route | Rôle requis |
|---------|-------|-------------|
| `GET` | `/login` | — |
| `GET` | `/admin` | Tous les authentifiés |
| `GET/POST` | `/admin/articles/create` | author+ |
| `GET/PATCH` | `/admin/articles/edit/:id` | author+ (propres articles) / editor+ (tous) |
| `GET/POST` | `/admin/articles/delete/:id` | editor+ |
| `POST` | `/admin/articles/publish/:id` | editor+ |
| `GET` | `/admin/articles/show/:id` | author+ |
| `GET/POST` | `/admin/categories/*` | editor+ |
| `GET/POST` | `/admin/tags/*` | editor+ |
| `GET/POST` | `/admin/users/*` | admin |
| `GET/POST` | `/admin/media/*` | Tous les authentifiés |

### Upload de médias

Contraintes de validation côté serveur :

| Type | Formats MIME acceptés | Taille max |
|------|-----------------------|-----------|
| Images | `image/jpeg`, `image/png`, `image/gif`, `image/webp` | 5 MB |
| Vidéos | `video/mp4`, `video/webm` | 50 MB |
| Audio | `audio/mpeg`, `audio/wav` | 10 MB |
| Documents | `application/pdf` | 10 MB |

---

## 8. Frontend public (Vite multi-pages)

Le frontend public est une application **multi-pages statique** construite avec Vite, consommant l'API REST.

### Pages

| Fichier | URL attendue | Description |
|---------|--------------|-------------|
| `index.html` | `/` | Accueil — articles récents |
| `pages/article.html` | `/article.html?slug=…` | Détail d'un article |
| `pages/category.html` | `/category.html?slug=…` | Articles d'une catégorie |
| `pages/tag.html` | `/tag.html?slug=…` | Articles d'un tag |
| `pages/search.html` | `/search.html?q=…` | Recherche plein texte |
| `pages/legal.html` | `/legal.html` | Mentions légales / RGPD |

### Client API — `src/js/services/api.js`

- `API_BASE` : `import.meta.env.VITE_API_URL ?? 'http://localhost:8079'`
- **Cache sessionStorage 15 minutes** pour les catégories et les tags (réduction des appels API)
- Fonctions exposées : `getArticles()`, `getArticleBySlug()`, `getCategories()`, `getTags()`, `getCategoryBySlug()`, `getTagBySlug()`, `getArticlesByTagId()`

### Consentement cookies — `src/js/components/cookieBanner.js`

- Affiche une bannière de consentement au premier chargement
- Stocke le choix dans `localStorage` sous la clé `cookie_consent` (`"accepted"` ou `"refused"`)
- Ne s'affiche pas si un choix a déjà été enregistré
- Conforme aux exigences RGPD

---

## 9. Sécurité

### 9.1 Authentification par session

- Connexion via `POST /api/v1/auth/login` ou le formulaire `/login`
- Mot de passe hashé avec `PASSWORD_BCRYPT` (PHP `password_hash` / `password_verify`)
- Session PHP native via `Session::start()` (wrapper maison)
- L'utilisateur en session est vérifié sur chaque route protégée

### 9.2 Protection CSRF — `Lib/Auth/CsrfToken.php`

- Token généré via `bin2hex(random_bytes(32))` → 64 caractères hexadécimaux
- **TTL : 1 heure** (3600 secondes), stocké en session avec son timestamp d'expiration
- Présent dans tous les formulaires d'administration (champ caché `csrf_token`)
- Validé côté serveur avant tout traitement de formulaire mutant

### 9.3 Contrôle d'accès — `Lib/Auth/PermissionService.php`

| Permission | admin | editor | author |
|-----------|-------|--------|--------|
| Gérer les utilisateurs | ✅ | ❌ | ❌ |
| Gérer catégories / tags | ✅ | ✅ | ❌ |
| Gérer tous les articles | ✅ | ✅ | ❌ |
| Publier des articles | ✅ | ✅ | ❌ |
| Gérer ses propres articles | ✅ | ✅ | ✅ |

### 9.4 Prévention des injections SQL

- Toutes les requêtes utilisent les **PDO Prepared Statements**
- Aucune concaténation de valeurs utilisateur dans les requêtes SQL
- Paramètres liés via `bindValue()` / `execute([…])`

---

## 10. Infrastructure Docker

### Services Docker Compose

| Service | Image | Port hôte → conteneur | Description |
|---------|-------|----------------------|-------------|
| `php-CMS` | Build depuis `Dockerfile` | `8079 → 80` | PHP 8.4 + Apache |
| `php-postgres-CMS` | `postgres:16-alpine` | `5433 → 5432` | PostgreSQL 16 |

### Volumes

| Volume | Chemin conteneur | Description |
|--------|-----------------|-------------|
| `./app` | `/var/www/html` | Code source PHP |
| `./log` | `/var/log/apache2/` | Logs Apache |
| `./dist` | `/var/www/html/dist` | Build Vite (production) |
| `./app/uploads` | `/var/www/html/uploads` | Médias uploadés |
| `./postgres` | `/var/lib/postgresql/data` | Données PostgreSQL (persistance) |
| `./database/init` | `/docker-entrypoint-initdb.d` | Scripts SQL d'initialisation |

### Initialisation de la base de données

Les scripts SQL dans `api/database/init/` sont exécutés automatiquement par PostgreSQL au premier démarrage, dans l'ordre alphabétique :

| Script | Tables créées |
|--------|---------------|
| `01-users.sql` | `users` + ENUM `user_role` + trigger `updated_at` |
| `02-categories.sql` | `category` |
| `03-articles.sql` | `articles` + ENUM `article_status` + indexes |
| `04-article_category.sql` | `article_categories` (table de jonction) |
| `05-tags.sql` | `tags` |
| `06-article_tag.sql` | `article_tags` (table de jonction) |
| `07-article_versions.sql` | `article_versions` |
| `08-media.sql` | `media` + `article_media` |

> Un compte administrateur par défaut est inséré par `01-users.sql` :  
> **Email :** `admin@cms.local` | **Mot de passe :** `admin123`

---

## 11. Guide d'installation

### Prérequis

- Docker Desktop (Windows / macOS / Linux)
- Node.js 18+ et npm (pour le frontend)
- Git

### Démarrage

```bash
# 1. Cloner le dépôt
git clone <url-du-repo>
cd projet-semestriel

# 2. Démarrer les services Docker (PHP + PostgreSQL)
cd api
docker-compose up -d --build

# L'API est accessible sur http://localhost:8079
# La BDD PostgreSQL est accessible sur localhost:5433

# 3. Démarrer le frontend (développement)
cd ../frontend
npm install
npm run dev

# Le frontend est accessible sur http://localhost:5173
```

### Build de production du frontend

```bash
cd frontend
npm run build
```

### Configuration BDD

La connexion est définie dans `api/app/config/database.json` :

```json
{
  "host": "php-framework-postgres",
  "user": "user",
  "password": "password",
  "database": "db",
  "port": 5432
}
```

> Le `host` correspond au nom du service Docker Compose (`php-framework-postgres`).


