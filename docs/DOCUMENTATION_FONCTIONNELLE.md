# Documentation Fonctionnelle — CMS Éditorial

---

## Table des matières

1. [Présentation du projet](#1-présentation-du-projet)
2. [Accès à l'application](#2-accès-à-lapplication)
3. [Rôles et permissions](#3-rôles-et-permissions)
4. [Gestion des articles](#4-gestion-des-articles)
5. [Gestion des catégories](#5-gestion-des-catégories)
6. [Gestion des tags](#6-gestion-des-tags)
7. [Gestion des médias](#7-gestion-des-médias)
8. [Gestion des utilisateurs](#8-gestion-des-utilisateurs)
9. [API REST publique](#9-api-rest-publique)
10. [Frontend public](#10-frontend-public)
11. [Conformité RGPD](#11-conformité-rgpd)
12. [Sécurité](#12-sécurité)

---

## 1. Présentation du projet

Le projet est un **CMS headless éditorial multi-auteurs** développé from scratch en PHP, avec un framework maison (router, ORM, contrôleurs), un back-office HTML rendu côté serveur, une API REST JSON, et un frontend public multi-pages construit avec Vite.

**Cas d'usage ciblé :** site éditorial / blog avec plusieurs rédacteurs, une chaîne de validation éditoriale (brouillon → publié → archivé), et un frontend public découplé consommant l'API.

**Stack :** PHP 8.4 · PostgreSQL 16 · Apache · Docker · Vite · SASS · modern-normalize

---

## 2. Accès à l'application

| Point d'entrée | URL locale | Description |
|----------------|-----------|-------------|
| Frontend public | `http://localhost:5173` | Site public (Vite dev server) |
| Back-office | `http://localhost:8079/admin` | Interface d'administration |
| Connexion | `http://localhost:8079/login` | Formulaire d'authentification |
| API REST | `http://localhost:8079/api/v1` | Endpoints JSON |

**Compte administrateur par défaut**
- Email : `admin@cms.local`
- Mot de passe : `admin123`

---

## 3. Rôles et permissions

L'application définit trois rôles stockés en ENUM PostgreSQL : `admin`, `editor`, `author`.

| Action | Admin | Editor | Author |
|--------|:-----:|:------:|:------:|
| Créer un article | ✅ | ✅ | ✅ |
| Modifier ses propres articles | ✅ | ✅ | ✅ |
| Modifier tous les articles | ✅ | ✅ | ❌ |
| Publier / archiver un article | ✅ | ✅ | ❌ |
| Gérer les catégories | ✅ | ✅ | ❌ |
| Gérer les tags | ✅ | ✅ | ❌ |
| Gérer les médias | ✅ | ✅ | ✅ |
| Gérer les utilisateurs | ✅ | ❌ | ❌ |

Les auteurs ne peuvent **pas publier seuls** : leurs articles restent en brouillon jusqu'à validation par un éditeur ou l'administrateur.

---

## 4. Gestion des articles

### 4.1 Workflow des statuts

```
draft ──► published ──► archived
  ▲            │
  └────────────┘  (dépublication possible)
```

| Statut | Visible publiquement | Modifiable par l'auteur |
|--------|:-------------------:|:-----------------------:|
| `draft` | ❌ | ✅ |
| `published` | ✅ | ✅ (editor/admin) |
| `archived` | ❌ | ✅ (editor/admin) |

### 4.2 Fonctionnalités de l'éditeur

- **Création** : titre, contenu (texte riche), extrait, slug (auto-généré depuis le titre)
- **Association** : lier un article à une ou plusieurs catégories et/ou tags
- **Aperçu avant publication** : consultation de l'article dans son rendu final avant de le publier
- **Publication / archivage** : action réservée aux editors et admins
- **Suppression** : action réservée aux editors et admins

### 4.3 Versioning

À chaque modification d'un article, un **snapshot** (titre + contenu) est automatiquement enregistré dans la table `article_versions`. L'historique est consultable depuis le back-office.

> ⚠️ La **restauration** d'une version antérieure n'a pas été implémentée dans cette version — seule la consultation est disponible.

### 4.4 Slug

Le slug est généré automatiquement depuis le titre (translittération + kebab-case). Il est unique et constitue l'identifiant SEO de l'article dans les URLs publiques.

---

## 5. Gestion des catégories

- **Création / modification / suppression** depuis `/admin/categories`
- Chaque catégorie possède : nom, slug, description
- Un article peut appartenir à **plusieurs catégories**
- La suppression d'une catégorie dissocie les articles (pas de suppression en cascade des articles)

---

## 6. Gestion des tags

Fonctionnement identique aux catégories :
- CRUD complet depuis `/admin/tags`
- Chaque tag : nom, slug, description
- Un article peut avoir **plusieurs tags**
- La recherche de tags est insensible à la casse

---

## 7. Gestion des médias

### 7.1 Médiathèque

Accessible depuis `/admin/media`, la médiathèque permet de :
- **Uploader** un fichier (image, vidéo, audio, PDF)
- Renseigner un **texte alternatif** (alt text) et un titre
- **Supprimer** un média
- **Associer** un média à un article (avec gestion de l'image mise en avant `is_featured` et de l'ordre d'affichage)

### 7.2 Contraintes d'upload

| Type | Formats acceptés | Taille maximale |
|------|-----------------|----------------|
| Images | JPEG, PNG, GIF, WebP | 5 MB |
| Vidéos | MP4, WebM | 50 MB |
| Audio | MP3, WAV | 10 MB |
| Documents | PDF | 10 MB |

La validation du type MIME est effectuée **côté serveur** (pas uniquement sur l'extension).

---

## 8. Gestion des utilisateurs

Accessible depuis `/admin/users` — réservé aux **administrateurs**.

| Fonctionnalité | Description |
|----------------|-------------|
| Lister les utilisateurs | Tableau de tous les comptes |
| Créer un utilisateur | Formulaire : prénom, nom, email, mot de passe, rôle |
| Modifier un utilisateur | Modification de tous les champs dont le rôle |
| Supprimer un utilisateur | Suppression avec cascade sur les articles associés |

Les mots de passe sont **hashés avec bcrypt** (`PASSWORD_BCRYPT`) avant stockage. Ils ne sont jamais stockés en clair.

---

## 9. API REST publique

Base URL : `http://localhost:8079/api/v1`

Les endpoints de **lecture** (`GET`) sont **publics** — aucune authentification requise.  
Les endpoints d'**écriture** (`POST`, `PATCH`, `DELETE`) requièrent une session active.

### Articles

| Méthode | Endpoint | Auth | Description |
|---------|----------|:----:|-------------|
| `GET` | `/articles` | ❌ | Liste des articles publiés |
| `GET` | `/articles/:id` | ❌ | Article par ID |
| `GET` | `/articles/slug/:slug` | ❌ | Article par slug |
| `GET` | `/articles/:id/categories` | ❌ | Catégories d'un article |
| `GET` | `/articles/:id/tags` | ❌ | Tags d'un article |
| `GET` | `/articles/:id/versions` | ✅ | Historique des versions |
| `GET` | `/articles/:id/versions/:vId` | ✅ | Une version spécifique |
| `POST` | `/articles` | ✅ | Créer un article |
| `PATCH` | `/articles/:id` | ✅ | Modifier un article |
| `PATCH` | `/articles/:id/publish` | ✅ | Publier |
| `PATCH` | `/articles/:id/archive` | ✅ | Archiver |
| `DELETE` | `/articles/:id` | ✅ | Supprimer |

### Catégories

| Méthode | Endpoint | Auth | Description |
|---------|----------|:----:|-------------|
| `GET` | `/categories` | ❌ | Liste des catégories |
| `GET` | `/categories/:id` | ❌ | Catégorie par ID |
| `GET` | `/categories/:id/articles` | ❌ | Articles d'une catégorie |
| `POST` | `/categories` | ✅ | Créer |
| `PATCH` | `/categories/:id` | ✅ | Modifier |
| `DELETE` | `/categories/:id` | ✅ | Supprimer |

### Tags

| Méthode | Endpoint | Auth | Description |
|---------|----------|:----:|-------------|
| `GET` | `/tags` | ❌ | Liste des tags |
| `GET` | `/tags/:id` | ❌ | Tag par ID |
| `GET` | `/tags/:id/articles` | ❌ | Articles d'un tag |
| `POST` | `/tags` | ✅ | Créer |
| `PATCH` | `/tags/:id` | ✅ | Modifier |
| `DELETE` | `/tags/:id` | ✅ | Supprimer |

### Utilisateurs

| Méthode | Endpoint | Auth | Description |
|---------|----------|:----:|-------------|
| `GET` | `/users` | ✅ admin | Liste |
| `GET` | `/users/:id` | ✅ admin | Un utilisateur |
| `POST` | `/users` | ✅ admin | Créer |
| `PATCH` | `/users/:id` | ✅ admin | Modifier |
| `DELETE` | `/users/:id` | ✅ admin | Supprimer |

### Authentification

| Méthode | Endpoint | Description |
|---------|----------|-------------|
| `POST` | `/auth/login` | Connexion |
| `GET` | `/auth/logout` | Déconnexion |
| `GET` | `/auth/profile` | Profil de l'utilisateur connecté |

---

## 10. Frontend public

Le frontend public est une **application multi-pages statique** construite avec Vite, découplée du backend. Elle consomme l'API REST en lecture seule.

### Pages disponibles

| Page | URL | Description |
|------|-----|-------------|
| Accueil | `/` | Liste paginée des articles publiés (10 par page) |
| Article | `/article.html?slug=…` | Détail d'un article avec ses catégories et tags |
| Catégorie | `/category.html?slug=…` | Articles filtrés par catégorie |
| Tag | `/tag.html?slug=…` | Articles filtrés par tag |
| Recherche | `/search.html?q=…` | Recherche plein-texte dans les titres et contenus |
| Mentions légales | `/legal.html` | Page RGPD / mentions légales |

### Optimisation des performances

Un **cache sessionStorage de 15 minutes** est appliqué sur les listes de catégories et de tags, afin de réduire le nombre d'appels API lors de la navigation.

---

## 11. Conformité RGPD

- **Bannière de consentement cookies** affichée au premier chargement du site public
- Le choix de l'utilisateur (`accepté` / `refusé`) est stocké dans `localStorage` sous la clé `cookie_consent`
- La bannière ne réapparaît pas si un choix a déjà été enregistré
- **Page de mentions légales** accessible depuis toutes les pages (`/legal.html`) : informations sur le responsable de traitement, les données collectées, les droits des utilisateurs (accès, rectification, suppression)
- **Droit à l'effacement** : un administrateur peut supprimer un compte utilisateur depuis le back-office, entraînant la suppression en cascade de ses données associées (articles)

---

## 12. Sécurité

| Mesure | Mise en œuvre |
|--------|--------------|
| Hashage des mots de passe | `password_hash()` avec `PASSWORD_BCRYPT` |
| Protection CSRF | Token 64 caractères (TTL 1h) sur tous les formulaires d'admin |
| Injections SQL | PDO Prepared Statements — aucune concaténation de données utilisateur |
| Contrôle d'accès | `PermissionService` vérifié sur chaque route protégée |
| Sessions sécurisées | `Session::start()` avec wrapper maison, régénération d'ID à la connexion |
| Validation serveur | Validation des types MIME et tailles de fichiers côté serveur avant tout upload |

---
