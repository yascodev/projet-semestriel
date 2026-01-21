# 🛠️ Guide du Développeur - Gestion des Articles

## 🎯 Objectif

Ce guide explique comment le système de gestion des articles fonctionne et comment l'étendre/maintenir.

---

## 📂 Structure du Projet

### Arborescence Implémentée
```
app/
├── src/
│   ├── Controllers/
│   │   ├── Admin/
│   │   │   ├── AdminController.php              [existant]
│   │   │   ├── ListArticlesController.php       [nouveau]
│   │   │   ├── FormCreateArticleController.php  [nouveau]
│   │   │   ├── CreateArticleActionController.php[nouveau]
│   │   │   ├── FormEditArticleController.php    [nouveau]
│   │   │   ├── UpdateArticleController.php      [nouveau]
│   │   │   ├── PublishArticleActionController.php[nouveau]
│   │   │   ├── ArchiveArticleActionController.php[nouveau]
│   │   │   └── DeleteArticleController.php      [nouveau]
│   │   └── Articles/
│   │       ├── CreateArticleController.php      [existant - API]
│   │       ├── GetArticlesController.php        [existant - API]
│   │       └── ... (autres endpoints API)
│   ├── Entities/
│   │   ├── Article.php         [existant]
│   │   ├── User.php            [existant]
│   │   ├── Category.php        [existant]
│   │   └── Tag.php             [existant]
│   ├── Repositories/
│   │   ├── ArticleRepository.php        [existant]
│   │   ├── CategoryRepository.php       [existant]
│   │   ├── TagRepository.php            [existant]
│   │   └── UserRepository.php           [existant]
│   └── Lib/
│       ├── Http/
│       │   ├── Request.php              [modifié - getPost/getQuery]
│       │   ├── Response.php             [existant]
│       │   └── Router.php               [existant]
│       ├── Auth/
│       │   ├── Session.php              [existant]
│       │   └── CsrfToken.php            [existant]
│       └── Controllers/
│           └── AbstractController.php   [existant - render method]
├── views/
│   └── admin/
│       ├── dashboard.html               [existant]
│       ├── articles.html                [nouveau]
│       ├── create-article.html          [nouveau]
│       └── edit-article.html            [nouveau]
└── config/
    └── routes.json                      [modifié - 9 routes]
```

---

## 🔄 Flux de Données

### Cycle de Requête
```
1. Utilisateur demande /admin/articles
   ↓
2. Router.php match la route dans routes.json
   ↓
3. ListArticlesController créé et traité
   ↓
4. Vérification authentification (Session)
   ↓
5. Chargement des données (ArticleRepository)
   ↓
6. Vérification des permissions
   ↓
7. Rendu de la vue (articles.html)
   ↓
8. Response retournée au navigateur
```

### Exemple: Créer un Article
```
1. GET /admin/articles/create
   → FormCreateArticleController::process()
   → Charge catégories, tags
   → Génère CSRF token
   → Rendu create-article.html
   ↓
2. Utilisateur remplisssle formulaire et soumet
   ↓
3. POST /admin/articles/create
   → CreateArticleActionController::process()
   → Validation CSRF
   → Validation des données
   → Création Article entity
   → ArticleRepository::create()
   → Insertion catégories (article_category)
   → Insertion tags (article_tag)
   → Redirection /admin/articles?success=created
```

---

## 🏗️ Pattern Architectural

### MVC Pattern

**Model** (Repositories + Entities)
```php
// ArticleRepository.php
$articleRepository = new ArticleRepository();
$article = $articleRepository->find($id);
$articles = $articleRepository->findByAuthor($userId);
$articleRepository->create($article);
$articleRepository->update($article);
$articleRepository->delete($article);
```

**View** (HTML templates)
```html
<!-- articles.html -->
<!-- Affichage des données -->
```

**Controller** (Logique métier)
```php
// ListArticlesController.php
public function process(Request $request): Response {
    // Authentification
    // Permissions
    // Chargement des données
    // Rendu de la vue
}
```

### Séparation des Responsabilités

**FormController** → Affiche un formulaire
- ListArticlesController
- FormCreateArticleController
- FormEditArticleController

**ActionController** → Traite une action
- CreateArticleActionController
- UpdateArticleController
- PublishArticleActionController
- ArchiveArticleActionController
- DeleteArticleController

---

## 🔐 Gestion de la Sécurité

### 1. Authentification
```php
// Toujours vérifier en début de controller
if (!Session::isAuthenticated()) {
    return Response::redirect('/login');
}

// Récupérer l'utilisateur
$currentUser = $userRepository->find(Session::get('user_id'));
```

### 2. Permissions
```php
// Vérifier le rôle
if ($currentUser->role === 'author' && $article->author_id !== $currentUserId) {
    return Response::redirect('/admin/articles?error=Permission+refusée');
}
```

### 3. Protection CSRF
```php
// Générer dans le controller
$csrfToken = CsrfToken::generate();

// Valider dans la soumission
$csrfToken = $request->getPost('csrf_token');
if (!$csrfToken || !CsrfToken::validate($csrfToken)) {
    return Response::redirect('/admin/articles?error=Erreur+CSRF');
}
```

### 4. Validation des Données
```php
// Toujours valider à la fois client (JS) et serveur (PHP)
if (strlen($title) < 3 || strlen($title) > 255) {
    return Response::redirect('/admin/articles/create?error=...');
}
```

### 5. Escaping HTML
```php
// Toujours échapper avant affichage
<?= htmlspecialchars($article->title) ?>

// Ne pas échapper avec htmlspecialchars() :
// - Contenu de <script> ou <style>
// - Attributs HTML (utiliser htmlspecialchars avec ENT_QUOTES)
```

---

## 🗄️ Base de Données

### Tables Principales

**articles**
```sql
id            INT PRIMARY KEY AUTO_INCREMENT
title         VARCHAR(255) NOT NULL
slug          VARCHAR(255) UNIQUE NOT NULL
content       TEXT
excerpt       TEXT
status        VARCHAR(50) DEFAULT 'draft'
author_id     INT NOT NULL FOREIGN KEY
created_at    TIMESTAMP
updated_at    TIMESTAMP
published_at  TIMESTAMP NULL
```

**article_category** (M:N)
```sql
article_id    INT NOT NULL FOREIGN KEY
category_id   INT NOT NULL FOREIGN KEY
PRIMARY KEY (article_id, category_id)
```

**article_tag** (M:N)
```sql
article_id    INT NOT NULL FOREIGN KEY
tag_id        INT NOT NULL FOREIGN KEY
PRIMARY KEY (article_id, tag_id)
```

### Queries Utiles

```sql
-- Récupérer tous les articles d'un auteur
SELECT * FROM articles WHERE author_id = :author_id;

-- Récupérer les articles publiés
SELECT * FROM articles WHERE status = 'published' ORDER BY published_at DESC;

-- Compter les brouillons d'un auteur
SELECT COUNT(*) FROM articles WHERE author_id = :author_id AND status = 'draft';

-- Articles avec leurs catégories
SELECT a.*, GROUP_CONCAT(c.name) as categories
FROM articles a
LEFT JOIN article_category ac ON a.id = ac.article_id
LEFT JOIN category c ON ac.category_id = c.id
GROUP BY a.id;
```

---

## 🧪 Guide des Tests

### Tester Localement

```bash
# 1. Démarrer le projet
docker-compose up -d --build

# 2. Créer un utilisateur test
docker exec -i projet-semst-postgres psql -U postgres << EOF
INSERT INTO "user" (email, password, role, created_at, updated_at)
VALUES ('test@example.com', '$2y$10$...', 'author', NOW(), NOW());
EOF

# 3. Accéder à /admin/articles
# Naviguer sur http://localhost/admin/articles
```

### Cas de Test Critiques

1. **Test de Création**
   ```
   1. Aller sur /admin/articles/create
   2. Remplir le formulaire
   3. Valider la requête POST
   4. Vérifier que l'article est créé
   ```

2. **Test de Permissions**
   ```
   1. Créer un article avec user A
   2. Essayer d'éditer avec user B (author)
   3. Vérifier permission refusée
   4. Essayer avec user admin
   5. Vérifier que ça marche
   ```

3. **Test CSRF**
   ```
   1. Intercepter la requête POST
   2. Modifier le CSRF token
   3. Vérifier rejet de la requête
   ```

### Exécuter les Tests (si configurés)

```bash
# Tests unitaires
php vendor/bin/phpunit

# Tests d'intégration
php vendor/bin/phpunit --testsuite=integration

# Avec couverture
php vendor/bin/phpunit --coverage-html coverage/
```

---

## 📝 Comment Ajouter une Nouvelle Fonctionnalité

### Exemple: Ajouter un filtre par catégorie

#### Étape 1: Modifier le Repository
```php
// ArticleRepository.php
public function findByAuthorAndCategory(int $authorId, int $categoryId): array {
    $sql = "SELECT a.* FROM articles a
            INNER JOIN article_category ac ON a.id = ac.article_id
            WHERE a.author_id = :author_id AND ac.category_id = :category_id";
    // ... exécuter et retourner
}
```

#### Étape 2: Modifier le Controller
```php
// ListArticlesController.php
$categoryId = $request->getQuery('category');
if ($categoryId) {
    $articles = $articleRepository->findByAuthorAndCategory($currentUser->id, $categoryId);
}
```

#### Étape 3: Modifier la Vue
```html
<!-- articles.html -->
<select name="category" onchange="this.form.submit()">
    <option value="">Toutes les catégories</option>
    <?php foreach ($categories as $cat): ?>
        <option value="<?= $cat->id ?>" <?= $categoryId === $cat->id ? 'selected' : '' ?>>
            <?= htmlspecialchars($cat->name) ?>
        </option>
    <?php endforeach; ?>
</select>
```

#### Étape 4: Ajouter la Route (si nécessaire)
```json
// config/routes.json
{
    "path": "/admin/articles/category/:id",
    "method": "GET",
    "controller": "Admin\\ListArticlesByCategoryController"
}
```

#### Étape 5: Tester
- Vérifier les permissions
- Vérifier la validation
- Tester les edge cases

---

## 🐛 Débogage

### Affichage des Erreurs

```php
// Enable error display (développement uniquement)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Logs
error_log('Debug message: ' . print_r($data, true));
```

### Vérifier les Sessions

```php
// Vérifier la session utilisateur
var_dump(Session::get('user_id'));

// Vérifier le CSRF token
var_dump(CsrfToken::get());
```

### Monitoring SQL

```php
// Ajouter les paramètres à la requête
$stmt->debugDumpParams();

// Vérifier les erreurs SQL
if (!$stmt->execute($params)) {
    error_log(json_encode($stmt->errorInfo()));
}
```

### Logs Docker

```bash
# Voir les logs temps réel
docker-compose logs -f app

# Voir les erreurs Apache
docker exec projet-semst-app tail -f /var/log/apache2/error.log

# Entrer dans le conteneur
docker exec -it projet-semst-app bash
```

---

## 📚 Ressources Utiles

### Documentation du Projet
- [GESTION_ARTICLES_DOCUMENTATION.md](GESTION_ARTICLES_DOCUMENTATION.md) - Doc technique
- [GUIDE_TEST_ARTICLES.md](GUIDE_TEST_ARTICLES.md) - Plan de test
- [RESUME_IMPLEMENTATION.md](RESUME_IMPLEMENTATION.md) - Résumé implémentation
- [DATABASE_SCHEMA.sql](DATABASE_SCHEMA.sql) - Schéma BD
- [CHECKLIST_DEPLOYMENT.md](CHECKLIST_DEPLOYMENT.md) - Checklist déploiement

### Concepts Importants
- [CSRF Protection](https://www.owasp.org/index.php/Cross-Site_Request_Forgery_(CSRF))
- [SQL Injection Prevention](https://www.owasp.org/index.php/SQL_Injection)
- [XSS Prevention](https://www.owasp.org/index.php/Cross-site_Scripting_(XSS))
- [Authentication Best Practices](https://cheatsheetseries.owasp.org/cheatsheets/Authentication_Cheat_Sheet.html)

### Outils Recommandés
- VSCode avec PHP Intelephense
- Docker Desktop
- Postman (pour tester les API)
- pgAdmin (pour gérer PostgreSQL)

---

## 🚀 Performance & Optimisation

### Optimisations Implémentées
- ✅ Pagination (10 articles par page)
- ✅ Indexes sur les colonnes clés
- ✅ Prepared statements (prévient les injections)
- ✅ Lazy loading des relations

### Optimisations à Ajouter
- ⏳ Mise en cache Redis
- ⏳ Full-text search
- ⏳ Minification CSS/JS
- ⏳ Compression Gzip
- ⏳ CDN pour les images

### Monitoring à Faire
- Taux d'erreur (< 1%)
- Temps de réponse (< 500ms)
- Utilisation mémoire
- Utilisation disque
- Connexions DB actives

---

## 🔗 Intégration avec d'Autres Systèmes

### Intégration avec l'API Articles Existante

L'implémentation du back-office **coexiste** avec l'API REST existante :

**API endpoints** (app/src/Controllers/Articles/)
```
GET    /articles              → GetArticlesController
POST   /articles              → CreateArticleController
PATCH  /articles/:id          → PatchArticleController
DELETE /articles/:id          → DeleteArticleController
```

**Back-office endpoints** (app/src/Controllers/Admin/)
```
GET    /admin/articles               → ListArticlesController
POST   /admin/articles/create        → CreateArticleActionController
POST   /admin/articles/:id/update    → UpdateArticleController
```

Les deux systèmes utilisent les mêmes **Repositories** et **Entities**, donc les modifications dans l'un affectent l'autre.

---

## 📞 Contacts & Support

### En Cas de Problème
1. Vérifier les logs Docker
2. Consulter la documentation
3. Vérifier les données de test

### Escalade
- Bug critique → Équipe de développement
- Question technique → Équipe de développement
- Problème utilisateur → Support client

---

## 📋 Checklist Avant de Commiter

- [ ] Code fonctionnellement correct
- [ ] Pas de `console.log()` ou `var_dump()`
- [ ] Tests locaux passent
- [ ] Code formaté
- [ ] Commit message clair et concis
- [ ] Pas de credentials pushées
- [ ] Documentation mise à jour

---

**Dernière mise à jour** : 21/01/2026  
**Version** : 1.0  
**Maintenu par** : Équipe de développement
