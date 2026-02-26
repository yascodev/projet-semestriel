# Documentation Organisationnelle — CMS Éditorial

## Table des matières

1. [Outils de gestion de projet](#1-outils-de-gestion-de-projet)
2. [Conventions Git](#2-conventions-git)
3. [Workflow de développement](#3-workflow-de-développement)
4. [Organisation des sprints](#4-organisation-des-sprints)
5. [Conventions de code](#5-conventions-de-code)
6. [Structure des issues GitHub](#6-structure-des-issues-github)

---

## 1. Outils de gestion de projet

### 1.1 GitHub Issues

L'ensemble du backlog produit est géré via les **GitHub Issues** du dépôt `yascodev/projet-semestriel`.

Chaque issue représente une user story ou une tâche technique. Elle est caractérisée par :
- Un **titre** au format conventionnel 
- Un **corps** contenant la description, les critères d'acceptation et les détails techniques
- Des **labels** de sprint (`Sprint 0` à `Sprint 4`) pour situer l'issue dans le planning
- Un **statut** : `Open` (en cours ou à faire) / `Closed` (réalisé)

### 1.2 GitHub Projects

Le tableau de bord **GitHub Projects** a été utilisé pour visualiser l'avancement des sprints sous forme de tableau Kanban. Les colonnes principales sont :

| Colonne | Signification |
|---------|---------------|
| Backlog | Issues planifiées, non commencées |
| In Progress | Issue en cours de développement |
| Done | Issue terminée et validée |

### 1.3 Labels utilisés

| Label | Usage |
|-------|-------|
| `Sprint 0` | Mise en place, infrastructure, architecture |
| `Sprint 1` | Authentification, utilisateurs, styles de base |
| `Sprint 2` | CRUD articles, catégories, tags, versioning |
| `Sprint 3` | Administration complète, API REST, frontend public |
| `Sprint 4` | Sécurité, RGPD, accessibilité, documentation |

---

## 2. Conventions Git

### 2.1 Convention de nommage des branches

Chaque fonctionnalité ou correction est développée sur une **branche dédiée**. Le nom de la branche suit le format :

```
<type>/<description-courte>
```

**Types de branches :**

| Préfixe | Usage |
|---------|-------|
| `feat/` | Nouvelle fonctionnalité |
| `fix/` | Correction de bug |
| `docs/` | Documentation uniquement |
| `chore/` | Maintenance, configuration, scripts |
| `style/` | Modifications de style/CSS (sans impact fonctionnel) |
| `sec/` | Correctif de sécurité |
| `refactor/` | Refactorisation sans ajout de fonctionnalité |

**Exemples de noms de branches réels du projet :**

```
feat/authentification-connexion-deconnexion
feat/crud-articles-admin
feat/api-rest-articles-categories-tags
feat/frontend-public-multi-pages
feat/gestion-medias-upload
feat/rgpd-cookies-consentement
fix/correction-permissions-editeur
sec/protection-csrf-formulaires
style/integration-modern-normalize
chore/configuration-du-deploiement-en-ligne-render-vercel
```

### 2.2 Convention de messages de commit

Les messages de commit respectent le standard **Conventional Commits** :

```
<type>(<portée>): <description courte>

[corps optionnel]
[footer optionnel]
```

**Types de commit :**

| Type | Usage |
|------|-------|
| `feat` | Nouvelle fonctionnalité |
| `fix` | Correction de bug |
| `docs` | Modification de documentation |
| `style` | Formatage, CSS (aucun impact logique) |
| `refactor` | Restructuration de code |
| `test` | Ajout ou modification de tests |
| `chore` | Mise à jour de dépendances, configuration |
| `sec` | Correction de sécurité |

**Exemples :**

```
feat(auth): ajouter la connexion par session PHP
fix(articles): corriger la génération du slug en cas de titre avec accents
docs(api): documenter les endpoints de l'API REST v1
chore(docker): configurer les volumes pour la persistance PostgreSQL
sec(csrf): ajouter la validation du token CSRF sur tous les formulaires
```

---

## 3. Workflow de développement

### 3.1 Cycle de vie d'une fonctionnalité

```
1. Création de l'issue GitHub (backlog)
        ↓
2. Création d'une branche dédiée depuis main
        ↓
3. Développement local + commits réguliers
        ↓
4. Push de la branche sur GitHub
        ↓
5. Merge vers main (via Pull Request ou merge direct)
        ↓
6. Fermeture de l'issue
```

### 3.2 Règles de merge

- La branche `main` est la branche de référence (production)
- **Une branche = une fonctionnalité ou correction**
- Le merge est effectué une fois la fonctionnalité complète et testée manuellement
- Les issues sont fermées automatiquement après validation

### 3.3 Environnement de développement

| Service | Commande de démarrage | URL locale |
|---------|----------------------|-----------|
| Backend PHP + PostgreSQL | `docker-compose up -d --build` (dans `api/`) | `http://localhost:8079` |
| Frontend Vite (dev) | `npm run dev` (dans `frontend/`) | `http://localhost:5173` |
| PostgreSQL | Inclus dans Docker Compose | `localhost:5433` |

---

## 4. Organisation des sprints

Le projet a été découpé en **5 sprints** (Sprint 0 à Sprint 4), chacun correspondant à une session de travail d'environ 1 à 2 semaines.

### Sprint 0 — Mise en place du projet

**Objectif :** Initialiser les fondations techniques et organisationnelles du projet.

| # | Titre | Statut |
|---|-------|--------|
| #1 | Initialisation du dépôt Git et configuration GitHub | ✅ Réalisé |
| #2 | Mise en place de l'environnement Docker (PHP + PostgreSQL) | ✅ Réalisé |
| #3 | Définition de l'architecture MVC et structure des dossiers | ✅ Réalisé |
| #4 | Rédaction du backlog initial (user stories) | ✅ Réalisé |

---

### Sprint 1 — Authentification & Base

**Objectif :** Implémenter l'authentification des utilisateurs, la gestion des comptes, et intégrer les styles de base.

> **Note :** Le framework (router, controllers, PDO, architecture) a été **fourni en cours** et adapté au projet ; il ne constitue pas lui-même une user story du backlog.

| # | Titre | Statut |
|---|-------|--------|
| #10 | Authentification : connexion et déconnexion | ✅ Réalisé |
| #11 | Gestion des utilisateurs (CRUD admin) | ✅ Réalisé |
| #34 | Intégration SASS et normalisation CSS | ✅ Réalisé |

---

### Sprint 2 — CRUD Articles, Catégories, Tags & Versioning

**Objectif :** Mettre en place la gestion complète du contenu éditorial avec historique des modifications.

| # | Titre | Statut |
|---|-------|--------|
| #12 | CRUD articles (création, modification, suppression) | ✅ Réalisé |
| #13 | Gestion des statuts d'articles (draft / published / archived) | ✅ Réalisé |
| #14 | CRUD catégories | ✅ Réalisé |
| #15 | CRUD tags | ✅ Réalisé |
| #16 | Association articles ↔ catégories | ✅ Réalisé |
| #17 | Association articles ↔ tags | ✅ Réalisé |
| #35 | Versioning des articles (historique des modifications) | ✅ Réalisé |

---

### Sprint 3 — Administration, API REST & Frontend Public

**Objectif :** Finaliser l'interface d'administration, exposer l'API REST complète, et développer le frontend public multi-pages.

| # | Titre | Statut |
|---|-------|--------|
| #18 | Interface d'administration complète (dashboard) | ✅ Réalisé |
| #19 | Gestion des médias (upload, suppression) | ✅ Réalisé |
| #20 | Aperçu d'un article avant publication | ✅ Réalisé |
| #21 | Pages publiques par auteur | ❌ Non réalisé (dette technique) |
| #22 | Filtrage et recherche côté admin | ✅ Réalisé |
| #23 | Pagination des listes | ✅ Réalisé |
| #33 | API REST — endpoints articles, catégories, tags | ✅ Réalisé |
| #40 | Frontend public — page d'accueil | ✅ Réalisé |
| #41 | Frontend public — page article | ✅ Réalisé |
| #42 | Frontend public — page catégorie | ✅ Réalisé |
| #43 | Frontend public — page tag | ✅ Réalisé |
| #44 | Frontend public — page recherche | ✅ Réalisé |
| #45 | Frontend public — navigation et composants partagés | ✅ Réalisé |
| #47 | API REST — endpoints utilisateurs | ✅ Réalisé |
| #48 | API REST — authentification (login/logout/profil) | ✅ Réalisé |
| #49 | API REST — versioning des articles | ✅ Réalisé |
| #52 | API REST — endpoints médias | ✅ Réalisé |
| #69 | Frontend public — page tag (compléments) | ✅ Réalisé |
| #70 | Frontend public — page recherche (compléments) | ✅ Réalisé |
| #71 | Correction des liens de navigation | ✅ Réalisé |

**Dette technique Sprint 3 :**
- **#21** (pages par auteur) : non réalisé faute de temps — les articles sont associés aux auteurs en base mais aucune page publique `/auteur/:id` n'a été développée.
- **#46** (restauration de versions) : la **consultation** de l'historique des versions est fonctionnelle ; la **restauration** d'une version antérieure n'a pas été implémentée.

---

### Sprint 4 — Sécurité, RGPD, Accessibilité & Documentation

**Objectif :** Renforcer la sécurité de l'application, assurer la conformité RGPD, améliorer l'accessibilité et documenter le projet.

| # | Titre | Statut |
|---|-------|--------|
| #24 | Sécurité — protection CSRF sur tous les formulaires | ✅ Réalisé |
| #25 | Documentation technique et organisationnelle | ✅ Réalisé |
| #72 | Accessibilité (attributs ARIA, contrastes, focus) | ✅ Réalisé |
| #77 | RGPD — bannière de consentement cookies | ✅ Réalisé |

---

## 5. Conventions de code

### 5.1 PHP — Conventions de nommage

| Élément | Convention | Exemple |
|---------|-----------|---------|
| Classes (Controller, Entity, Repository) | PascalCase | `CreateArticleController`, `ArticleRepository` |
| Méthodes et fonctions | camelCase | `findBySlug()`, `requireRoles()` |
| Variables | camelCase | `$articleRepository`, `$currentUser` |
| Constantes | SCREAMING_SNAKE_CASE | `TOKEN_KEY`, `TOKEN_EXPIRY` |
| Fichiers PHP | PascalCase (même nom que la classe) | `AbstractController.php` |
| Namespaces | PascalCase, reflète la structure de dossiers | `App\Controllers\Admin\Articles` |

### 5.2 PHP — Organisation PSR-4

```
Namespace App\    →   api/app/src/
App\Controllers\  →   src/Controllers/
App\Entities\     →   src/Entities/
App\Repositories\ →   src/Repositories/
App\Lib\          →   src/Lib/
```

### 5.3 Principes appliqués

- **Responsabilité unique** : un contrôleur = une action (ex. `CreateArticleController`, `DeleteArticleController`)
- **Abstraction** : logique commune dans `AbstractController`, `AbstractRepository`
- **Pas de dépendances tierces** : aucun framework externe (contrainte académique)
- **Séparation des préoccupations** : Controllers ne contiennent pas de SQL, Repositories ne contiennent pas de logique métier

### 5.4 JavaScript — Conventions frontend

| Élément | Convention | Exemple |
|---------|-----------|---------|
| Fonctions | camelCase | `getArticles()`, `renderArticleCard()` |
| Variables | camelCase | `articleSlug`, `apiBase` |
| Constantes de module | SCREAMING_SNAKE_CASE | `API_BASE`, `CACHE_TTL` |
| Fichiers composants | camelCase | `cookieBanner.js`, `articleCard.js` |

### 5.5 CSS/SCSS — Conventions

- **BEM** (Block-Element-Modifier) pour le nommage des classes CSS
- Fichiers SCSS découpés par composant / page dans `src/css/components/` et `src/css/pages/`
- Import de `modern-normalize` via `@use` (module SCSS)

### 5.6 SQL — Conventions

- Noms de tables en **snake_case pluriel** (ex. `articles`, `article_versions`)
- Noms de colonnes en **snake_case** (ex. `author_id`, `created_at`, `is_featured`)
- Clés primaires toujours nommées `id`
- Clés étrangères nommées `<table_référencée>_id`

---

## 6. Structure des issues GitHub

### 6.1 Format des titres d'issues

Les issues suivent une convention de préfixe pour faciliter la lecture du backlog :

| Préfixe | Catégorie |
|---------|-----------|
| `feat:` | Nouvelle fonctionnalité |
| `ux:` | Expérience utilisateur, interface |
| `sec:` | Sécurité |
| `docs:` | Documentation |
| `fix:` | Correction de bug |
| `chore:` | Configuration, maintenance |
| `style:` | Style / CSS |

**Exemples d'issues réelles du projet :**

```
feat: authentification connexion / déconnexion
feat: CRUD articles dans l'interface d'administration
feat: API REST - endpoints articles, catégories, tags
feat: versioning des articles
ux: frontend public - page d'accueil des articles
sec: protection CSRF sur les formulaires d'administration
docs: documentation technique et organisationnelle
```

### 6.2 Corps d'une issue

Chaque issue contient au minimum :

1. **Description** : contexte et objectif de la fonctionnalité
2. **Critères d'acceptation** : liste des conditions à remplir pour considérer l'issue comme réalisée
3. **Notes techniques** (optionnel) : contraintes d'implémentation, endpoints concernés, tables impliquées

