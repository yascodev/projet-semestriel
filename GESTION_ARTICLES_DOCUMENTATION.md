# Gestion des Articles - Back-office
## Documentation d'Implémentation

### 📋 Sommaire
1. Architecture
2. Fichiers créés
3. Routes
4. Fonctionnalités implémentées
5. Critères d'acceptation
6. Sécurité et permissions
7. Guide d'utilisation

---

## 1. Architecture

### Pattern MVC Utilisé
- **Controllers** : Gestion de la logique métier et permissions
- **Views** : Fichiers HTML avec CSS inline
- **Repositories** : Accès aux données (ArticleRepository, CategoryRepository, TagRepository, UserRepository)
- **Entities** : Classes de modèles (Article, User, Category, Tag)

### Structure des dossiers
```
app/
├── src/
│   ├── Controllers/
│   │   └── Admin/
│   │       ├── ListArticlesController.php              (Affiche la liste)
│   │       ├── FormCreateArticleController.php         (Affiche le formulaire)
│   │       ├── CreateArticleActionController.php       (Traite la création)
│   │       ├── FormEditArticleController.php           (Affiche le formulaire d'édition)
│   │       ├── UpdateArticleController.php             (Traite la mise à jour)
│   │       ├── PublishArticleActionController.php      (Publie l'article)
│   │       ├── ArchiveArticleActionController.php      (Archive l'article)
│   │       └── DeleteArticleController.php             (Supprime l'article)
│   ├── Repositories/
│   │   └── (Déjà existants : ArticleRepository, CategoryRepository, TagRepository, UserRepository)
│   └── Lib/
│       └── Http/
│           └── Request.php                              (Amélioré avec getQuery et getPost)
├── views/
│   └── admin/
│       ├── articles.html                               (Liste des articles)
│       ├── create-article.html                         (Formulaire de création)
│       └── edit-article.html                           (Formulaire d'édition)
└── config/
    └── routes.json                                     (Routes mises à jour)
```

---

## 2. Fichiers Créés

### Controllers (app/src/Controllers/Admin/)

#### ListArticlesController.php
- **Rôle** : Affiche la liste des articles avec filtrage et pagination
- **Authentification** : Requise
- **Permissions** : 
  - Admin : Voit tous les articles
  - Editor/Author : Voit uniquement ses articles
- **Paramètres GET** :
  - `status` : Filtrer par draft/published/archived
  - `page` : Numéro de page
- **Données envoyées à la vue** :
  - articles[], currentUser, status, page, totalPages, totalArticles, perPage, csrf_token

#### FormCreateArticleController.php
- **Rôle** : Affiche le formulaire de création
- **Authentification** : Requise
- **Données envoyées** : categories[], tags[], csrf_token

#### CreateArticleActionController.php
- **Rôle** : Traite la création d'un article
- **Méthode** : POST
- **Validation** :
  - Titre : 3-255 caractères
  - Extrait : 10-500 caractères
  - Contenu : minimum 10 caractères
- **Protection CSRF** : Oui
- **Champs acceptés** :
  - title, excerpt, content, categories[], tags[], action (draft|publish)

#### FormEditArticleController.php
- **Rôle** : Affiche le formulaire d'édition
- **Authentification** : Requise
- **Permissions** : Author ne peut éditer que ses articles
- **Données pré-remplies** : Article existant avec catégories et tags

#### UpdateArticleController.php
- **Rôle** : Traite la mise à jour d'un article
- **Méthode** : POST
- **Validation** : Idem CreateArticleActionController
- **Protection CSRF** : Oui
- **Slug** : Régénéré automatiquement si le titre change

#### PublishArticleActionController.php
- **Rôle** : Change le statut à "published"
- **Méthode** : POST
- **Protection CSRF** : Oui
- **Permissions** : Author ne peut publier que ses articles

#### ArchiveArticleActionController.php
- **Rôle** : Change le statut à "archived"
- **Méthode** : POST
- **Protection CSRF** : Oui
- **Permissions** : Author ne peut archiver que ses articles

#### DeleteArticleController.php
- **Rôle** : Supprime un article
- **Méthode** : POST
- **Protection CSRF** : Oui
- **Permissions** : Admin ou propriétaire uniquement
- **Nettoyage** : Supprime aussi les associations (catégories, tags, versions)

### Views (app/views/admin/)

#### articles.html
**Fonctionnalités** :
- Tableau avec colonnes : Titre, Auteur, Statut, Date de création
- Filtrage par statut (Brouillon/Publié/Archivé)
- Pagination avec lien "Première/Précédente/Suivante/Dernière"
- Actions contextuelles :
  - Éditer (tous les statuts)
  - Publier (brouillon → publié)
  - Archiver (publié → archivé)
  - Republier (archivé → publié)
  - Supprimer (avec confirmation)
- Messages de feedback (succès/erreur)
- Badges de statut avec couleurs
- Design responsive
- CSS inline optimisé

#### create-article.html
**Fonctionnalités** :
- Sections groupées (Informations de base, Contenu, Catégories, Tags)
- Champs :
  - Titre* (3-255 caractères)
  - Extrait* (10-500 caractères avec compteur)
  - Contenu* (10+ caractères)
  - Catégories (optionnel, checkbox)
  - Tags (optionnel, checkbox)
- Validation côté client JavaScript
- Messages d'erreur personnalisés
- Boutons : "Enregistrer en brouillon" ou "Créer et publier"
- Design responsive
- CSS inline

#### edit-article.html
**Fonctionnalités** :
- Identique à create-article.html + :
- Pré-remplissage des données
- Statut modifiable (draft/published/archived)
- Affichage de la date de création/modification
- Date de publication si publié
- Bouton "Retour à la liste"

### Fichiers Modifiés

#### app/config/routes.json
**Routes ajoutées** :
```json
GET     /admin/articles                    → ListArticlesController
GET     /admin/articles/create             → FormCreateArticleController
POST    /admin/articles/create             → CreateArticleActionController
GET     /admin/articles/:id/edit           → FormEditArticleController
POST    /admin/articles/:id/update         → UpdateArticleController
POST    /admin/articles/:id/publish        → PublishArticleActionController
POST    /admin/articles/:id/archive        → ArchiveArticleActionController
POST    /admin/articles/:id/delete         → DeleteArticleController
```

#### app/src/Lib/Http/Request.php
**Méthodes ajoutées** :
- `getPost(string $key = null, mixed $default = null): mixed`
  - Retourne un champ POST spécifique ou tout $_POST
- `getQuery(string $key = null, mixed $default = null): mixed`
  - Retourne un paramètre GET spécifique ou tout $_GET

---

## 3. Routes

| Méthode | Chemin | Controller | Description |
|---------|--------|-----------|-------------|
| GET | `/admin/articles` | ListArticlesController | Liste des articles |
| GET | `/admin/articles/create` | FormCreateArticleController | Formulaire de création |
| POST | `/admin/articles/create` | CreateArticleActionController | Créer un article |
| GET | `/admin/articles/:id/edit` | FormEditArticleController | Formulaire d'édition |
| POST | `/admin/articles/:id/update` | UpdateArticleController | Mettre à jour un article |
| POST | `/admin/articles/:id/publish` | PublishArticleActionController | Publier un article |
| POST | `/admin/articles/:id/archive` | ArchiveArticleActionController | Archiver un article |
| POST | `/admin/articles/:id/delete` | DeleteArticleController | Supprimer un article |

---

## 4. Fonctionnalités Implémentées

### ✅ Liste des articles
- [x] Page avec tableau (titre, auteur, statut, date)
- [x] Filtrage par statut (draft, published, archived)
- [x] Pagination (10 articles par page)
- [x] Bouton pour créer un article

### ✅ Création d'article
- [x] Formulaire avec champs obligatoires (titre, contenu, excerpt)
- [x] Sélection de catégories (optionnel)
- [x] Sélection de tags (optionnel)
- [x] Actions : "Enregistrer en brouillon" ou "Créer et publier"
- [x] Génération automatique du slug unique

### ✅ Modification d'article
- [x] Formulaire pré-rempli avec les données existantes
- [x] Modification du titre, contenu, extrait
- [x] Modification des catégories et tags
- [x] Modification du statut
- [x] Slug régénéré si titre change

### ✅ Boutons de publication/archivage
- [x] Bouton "Publier" (brouillon → publié)
- [x] Bouton "Archiver" (publié → archivé)
- [x] Bouton "Republier" (archivé → publié)
- [x] Date de publication définie automatiquement

### ✅ Suppression d'article
- [x] Bouton de suppression avec confirmation
- [x] Nettoyage des associations (catégories, tags, versions)

### ✅ Validation des formulaires
- [x] Validation côté client (JavaScript)
- [x] Validation côté serveur (PHP)
- [x] Messages d'erreur personnalisés
- [x] Affichage des erreurs dans la page

### ✅ Messages de succès/erreur
- [x] Alertes en haut de la page
- [x] Codes : created, updated, published, archived, deleted
- [x] Messages contextuel selon l'action

### ✅ Protection CSRF
- [x] Token généré pour chaque formulaire
- [x] Validation avant chaque action
- [x] Tokens à expiration (3600s)

---

## 5. Critères d'Acceptation

| Critère | État | Notes |
|---------|------|-------|
| L'utilisateur peut voir la liste de ses articles | ✅ | Filtrage par author_id pour les non-admins |
| L'utilisateur peut créer un nouvel article | ✅ | Formulaire avec validation |
| L'utilisateur peut modifier un article | ✅ | Permissions respectées |
| L'utilisateur peut publier/archiver un article | ✅ | Boutons dynamiques selon le statut |
| L'utilisateur peut supprimer un article | ✅ | Confirmation + cleanup |
| Les permissions sont respectées (admin/editor/author) | ✅ | Vérification dans chaque controller |
| Validation des formulaires | ✅ | Client + serveur |
| Messages de succès/erreur | ✅ | Feedback utilisateur |
| Protection CSRF | ✅ | Tokens présents et validés |

---

## 6. Sécurité et Permissions

### Authentification
- Tous les endpoints requièrent `Session::isAuthenticated()`
- Redirection vers `/login` si non authentifié

### Permissions par Rôle

**Admin** :
- Voir tous les articles
- Créer, modifier, publier, archiver, supprimer n'importe quel article
- Accès à tous les statuts

**Editor** :
- Voir uniquement ses articles
- Créer, modifier, publier, archiver, supprimer ses propres articles
- Accès à tous les statuts

**Author** :
- Voir uniquement ses articles
- Créer, modifier, publier, archiver, supprimer ses propres articles
- Limitations plus strictes que l'Editor

### Protection CSRF
- Tous les formulaires incluent un token CSRF
- Validation avec `CsrfToken::validate()`
- Tokens expiration après 1 heure

### Validation des Données
- **Titre** : 3-255 caractères
- **Extrait** : 10-500 caractères
- **Contenu** : minimum 10 caractères
- **Catégories/Tags** : Vérification de l'existence en base

### Nettoyage des Données
- `htmlspecialchars()` sur l'affichage
- `trim()` sur les inputs
- Escaping au niveau SQL via PDO prepared statements

---

## 7. Guide d'Utilisation

### Accéder à la gestion des articles
1. Authentifiez-vous sur `/login`
2. Allez sur `/admin/articles`

### Créer un article
1. Cliquez sur "+ Créer un article"
2. Remplissez le formulaire
3. Sélectionnez des catégories/tags (optionnel)
4. Choisissez : "Enregistrer en brouillon" ou "Créer et publier"

### Modifier un article
1. Cliquez sur "✎ Éditer" dans le tableau
2. Modifiez les champs
3. Cliquez sur "💾 Enregistrer les modifications"

### Publier/Archiver un article
1. Dans le tableau, utilisez les boutons contextuels
2. "✓ Publier" : brouillon → publié
3. "📦 Archiver" : publié → archivé
4. "✓ Republier" : archivé → publié

### Filtrer les articles
1. Utilisez le dropdown "Filtrer par statut"
2. Options : Tous, Brouillons, Publiés, Archivés

### Supprimer un article
1. Cliquez sur "🗑️ Supprimer"
2. Confirmez l'action (irréversible)
3. L'article et ses associations sont supprimés

---

## 8. Améliorations Futures Possibles

1. **Bulk Actions** : Actions en masse sur plusieurs articles
2. **Recherche Avancée** : Recherche par titre, auteur, contenu
3. **Export** : Exporter les articles en CSV/JSON
4. **Versioning Avancé** : Gérer les versions d'article
5. **Scheduler** : Planifier la publication automatique
6. **Aperçu** : Aperçu avant publication
7. **Collaboration** : Commentaires entre éditeurs
8. **Analytics** : Statistiques de consultation

---

## 9. Tests Recommandés

### Fonctionnels
- [ ] Créer un article en brouillon
- [ ] Publier un brouillon
- [ ] Modifier un article
- [ ] Archiver un article
- [ ] Supprimer un article
- [ ] Filtrer par statut
- [ ] Paginer

### Sécurité
- [ ] Tenter d'accéder sans authentification
- [ ] Author ne peut pas modifier un article d'un autre
- [ ] CSRF token invalide
- [ ] Données malveillantes (injection HTML/SQL)

### Edge Cases
- [ ] Article avec titre très long
- [ ] Contenu avec caractères spéciaux
- [ ] Beaucoup de catégories/tags sélectionnées
- [ ] Suppression rapide successive

---

**Dernière mise à jour** : 21/01/2026
**Développeur** : GitHub Copilot
**Status** : ✅ Implémentation terminée
