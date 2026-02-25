# CAHIER DES CHARGES – PROJET SEMESTRIEL

## DevFlow CMS – CMS Blog Multi-Auteurs

**Établissement :** SAS Decode  
**Formation :** Titre Professionnel Chef de Projet Digital (RNCP39235 – Niveau 6)  
**Bloc évalué :** Bloc 2 – Management opérationnel du projet digital  
**Promotion :** 3A Web Development – S1 – 2025-2026  
**Année universitaire :** 2025-2026  
**Statut du document :** Version 2 – Améliorée et validée

---

## TABLE DES MATIÈRES

1. [Contexte et justification du projet](#1-contexte-et-justification-du-projet)
2. [Objectifs du projet](#2-objectifs-du-projet)
3. [Périmètre fonctionnel](#3-périmètre-fonctionnel)
4. [Exigences fonctionnelles détaillées](#4-exigences-fonctionnelles-détaillées)
5. [Exigences techniques](#5-exigences-techniques)
6. [Exigences de sécurité](#6-exigences-de-sécurité)
7. [Contraintes et limites](#7-contraintes-et-limites)
8. [Critères d'acceptation](#8-critères-dacceptation)
9. [Livrables attendus](#9-livrables-attendus)
10. [Métriques de qualité](#10-métriques-de-qualité)

---

## 1. CONTEXTE ET JUSTIFICATION DU PROJET

### 1.1. Contexte général

Dans le cadre du projet semestriel de troisième année Web Development, l'équipe projet est amenée à concevoir et développer un **CMS (Content Management System) depuis zéro**, en PHP orienté objet, reposant sur un **framework maison** conçu spécifiquement pour ce projet.

Ce projet s'inscrit dans une **mise en situation professionnelle simulée**, visant à reproduire les conditions réelles d'un projet digital en entreprise. Les étudiants sont positionnés non uniquement comme développeurs, mais comme **chefs de projet digitaux**, responsables du :

- **Cadrage fonctionnel** et des spécifications
- **Choix techniques** et leur justification
- **Organisation du travail** et planification
- **Suivi de l'avancement** et de la qualité
- **Livraison finale** et déploiement du produit

Le CMS développé, baptisé **DevFlow CMS**, prend la forme d'un **blog multi-auteurs** spécialisé dans les thématiques technologie et développement web. Il permet à plusieurs contributeurs de produire, gérer et publier des contenus éditoriaux au sein d'un environnement structuré, sécurisé et contrôlé, reposant sur un **workflow éditorial clair**.

### 1.2. Justification du choix technique

Le choix de développer un CMS **from scratch** répond à plusieurs objectifs stratégiques et pédagogiques :

#### Avantages pédagogiques

- Comprendre en profondeur les **mécanismes fondamentaux** d'un système de gestion de contenu moderne (routing, contrôleurs, gestion des données, sécurité)
- Maîtriser les **concepts clés** de framework, d'architecture logicielle et de programmation orientée objet
- Appréhender concrètement les **enjeux de sécurité applicative** (authentification, autorisation, protection des données)
- Expérimenter la **gestion complète** d'un projet digital, depuis la phase de cadrage jusqu'à la livraison finale

#### Objectif professionnel

Ce choix permet également de **démontrer la capacité à faire des arbitrages techniques et fonctionnels** dans un contexte contraint, compétence essentielle attendue d'un chef de projet digital.

### 1.3. Cas d'usage retenu

DevFlow CMS est conçu comme un **outil éditorial interne** destiné à une équipe de rédacteurs et de contributeurs spécialisés dans les domaines de la tech et du développement web.

#### Fonctionnalités principales offertes

- ✅ Création et gestion d'articles par plusieurs auteurs
- ✅ Structuration des contenus via des catégories et des tags
- ✅ Gestion des médias associés aux publications
- ✅ Mise en place d'un workflow éditorial basé sur des statuts explicites
- ✅ Versioning minimal permettant de conserver l'historique des modifications
- ✅ API publique en lecture seule pour intégration externe

#### Justification du choix

Ce cas d'usage couvre l'ensemble des fonctionnalités essentielles d'un CMS professionnel tout en restant **cohérent avec les contraintes d'un projet semestriel** et permet de valider les compétences en gestion de projet digital.

---

## 2. OBJECTIFS DU PROJET

### 2.1. Objectif principal

Concevoir et livrer un **CMS fonctionnel, sécurisé et évolutif**, tout en démontrant la maîtrise des compétences techniques, organisationnelles et méthodologiques attendues dans le cadre du **Bloc 2 – Management opérationnel du projet digital**.

La réussite du projet ne se mesure pas uniquement à la livraison d'un produit fonctionnel, mais également à :

- La qualité du **pilotage du projet**
- La **cohérence des choix** effectués
- La **capacité à justifier** ces décisions devant un jury professionnel

### 2.2. Objectifs spécifiques

#### Objectifs techniques

- [ ] Concevoir un **framework PHP maison** respectant les principes de la programmation orientée objet
- [ ] Structurer le code selon les **standards PSR-4** afin d'assurer lisibilité et maintenabilité
- [ ] Mettre en place un **système de publication multi-auteurs** avec gestion fine des rôles et permissions
- [ ] Développer une **API publique en lecture seule** permettant l'exploitation des contenus par un frontend externe
- [ ] Implémenter un **système de versioning minimal** des articles pour conserver l'historique des modifications
- [ ] Garantir un **niveau de sécurité conforme** aux bonnes pratiques du développement web (OWASP Top 10)
- [ ] Assurer **un temps de réponse acceptable** (< 2 secondes) pour tous les endpoints

#### Objectifs fonctionnels

- [ ] Proposer une **interface d'administration** claire, ergonomique et responsive
- [ ] Permettre une **gestion complète** des contenus éditoriaux (articles, catégories, tags, médias)
- [ ] Mettre en place un **workflow de publication structuré** avec des statuts explicites
- [ ] Assurer une **expérience utilisateur cohérente** selon les profils (administrateur, éditeur, auteur)
- [ ] Fournir des **retours clairs** à l'utilisateur sur les actions effectuées

#### Objectifs pédagogiques et managériaux

- [ ] **Planifier et organiser** un projet digital de manière professionnelle
- [ ] **Identifier, analyser et anticiper** les risques techniques et organisationnels
- [ ] **Coordonner le travail en équipe** à l'aide d'outils collaboratifs
- [ ] **Suivre la production** selon une logique agile avec sprints
- [ ] **Présenter, argumenter et défendre** les choix réalisés lors d'une soutenance orale

---

## 3. PÉRIMÈTRE FONCTIONNEL

### 3.1. Modules de gestion principaux

#### 3.1.1. Gestion des articles

| Fonctionnalité              | Description                                  | Responsable                                    |
| --------------------------- | -------------------------------------------- | ---------------------------------------------- |
| Création                    | Création de brouillons d'articles            | Tous (Admin/Éditeur/Auteur)                    |
| Édition                     | Modification du contenu et des métadonnées   | Auteur (propre) / Éditeur / Admin              |
| Publication                 | Passage du statut « brouillon » à « publié » | Éditeur / Admin (Auteur ≠ publication directe) |
| Archivage                   | Passage du statut « publié » à « archivé »   | Éditeur / Admin                                |
| Suppression                 | Suppression logique des articles             | Éditeur / Admin                                |
| Association catégories/tags | Liaison N:N avec les taxonomies              | Tous (Auto-attribution autorisée)              |
| Gestion image à la une      | Association d'une image d'illustration       | Éditeur / Admin                                |
| Versioning                  | Historique des modifications                 | Tous (consultation)                            |

#### 3.1.2. Gestion des catégories et tags

| Fonctionnalité   | Description                            | Responsable     |
| ---------------- | -------------------------------------- | --------------- |
| Création         | Création de catégories/tags            | Admin / Éditeur |
| Modification     | Édition du nom et de la description    | Admin / Éditeur |
| Suppression      | Suppression avec gestion des relations | Admin / Éditeur |
| Slug automatique | Génération unique et cohérente         | Automatisée     |
| Relations N:N    | Gestion flexible avec les articles     | Système         |

#### 3.1.3. Gestion des médias

| Fonctionnalité          | Description                       | Responsable     |
| ----------------------- | --------------------------------- | --------------- |
| Upload sécurisé         | Téléchargement avec validation    | Tous            |
| Validation formats      | Images, vidéos, PDF, audio        | Système         |
| Gestion tailles         | Limites par type de fichier       | Système         |
| Médiathèque personnelle | Consultation des propres fichiers | Tous            |
| Métadonnées             | Titre, description, alt-text      | Tous            |
| Association articles    | Liaison flexible aux contenus     | Éditeur / Admin |

#### 3.1.4. Gestion des utilisateurs

| Fonctionnalité    | Description                         | Responsable                          |
| ----------------- | ----------------------------------- | ------------------------------------ |
| Création          | Création de comptes utilisateurs    | Admin                                |
| Modification      | Édition du profil et des droits     | Admin (autre) / Tous (profil propre) |
| Suppression       | Suppression logique des comptes     | Admin                                |
| Attribution rôles | Assignation de rôles de permissions | Admin                                |
| Authentification  | Connexion par email/mot de passe    | Tous                                 |
| Session           | Gestion sécurisée des sessions      | Système                              |
| Statistiques      | Suivi de l'activité par utilisateur | Admin                                |

#### 3.1.5. Interface d'administration

| Fonctionnalité        | Description                      | Type               |
| --------------------- | -------------------------------- | ------------------ |
| Tableau de bord       | Vue synthétique avec KPIs        | HTML/CSS           |
| Navigation latérale   | Menu responsive et intuitif      | HTML/CSS           |
| Messagerie feedback   | Notifications de succès/erreur   | JavaScript/Backend |
| Protection CSRF       | Tokens sur tous les formulaires  | Backend            |
| Affichage conditionné | Visibilité selon les permissions | HTML/PHP           |

#### 3.1.6. API publique REST

Voir section [4.7 API publique](#47-api-publique)

### 3.2. Statuts d'article

| Statut           | Signification           | Visible publiquement | Modifiable par auteur                  |
| ---------------- | ----------------------- | -------------------- | -------------------------------------- |
| `draft`          | Brouillon               | ❌ Non               | ✅ Oui                                 |
| `pending_review` | En attente de relecture | ❌ Non               | ❌ Non (sauf auteur = admin)           |
| `published`      | Publié                  | ✅ Oui               | ✅ Oui (mais nécessite re-publication) |
| `archived`       | Archivé                 | ❌ Non               | ❌ Non                                 |

### 3.3. Rôles et permissions

| Rôle               | Création article | Édition propre | Édition autre | Publication | Suppression | Gestion users | Gestion catégories |
| ------------------ | ---------------- | -------------- | ------------- | ----------- | ----------- | ------------- | ------------------ |
| **Auteur**         | ✅               | ✅             | ❌            | ❌          | ✅ (propre) | ❌            | ❌                 |
| **Éditeur**        | ✅               | ✅             | ✅            | ✅          | ✅          | ❌            | ✅                 |
| **Administrateur** | ✅               | ✅             | ✅            | ✅          | ✅          | ✅            | ✅                 |

---

## 4. EXIGENCES FONCTIONNELLES DÉTAILLÉES

### 4.1. Authentification et autorisation

#### 4.1.1. Système d'authentification

- **Mécanisme :** Connexion par email et mot de passe sécurisée par sessions PHP
- **Hachage :** Algorithme bcrypt via `password_hash()` (non réversible)
- **Vérification :** Via `password_verify()` uniquement
- **Durée session :** À définir (recommandé : 1 heure d'inactivité)
- **Régénération :** Après chaque connexion pour prévenir le vol de session

#### 4.1.2. Modèle de permissions

Trois rôles hiérarchiques :

1. **Auteur** : Accès limité à ses propres contenus
2. **Éditeur** : Accès complet aux contenus + gestion des catégories/tags
3. **Administrateur** : Accès complet au système + gestion des utilisateurs

#### 4.1.3. Vérification des permissions

- ✅ Vérification à chaque requête sensible
- ✅ Redirection 403 en cas d'accès non autorisé
- ✅ Matrice de permissions clairement documentée dans le code
- ✅ Messages d'erreur explicites

### 4.2. Gestion des articles

#### 4.2.1. Cycle de vie complet

1. **Création** : Utilisateur crée un brouillon
2. **Édition** : Modifications du contenu et métadonnées
3. **Soumission** : Passage au statut `pending_review` (auteur) ou publication directe (éditeur/admin)
4. **Relecture** : Validation par éditeur/admin
5. **Publication** : Passage au statut `published` avec timestamp `published_at`
6. **Archivage** : Passage au statut `archived` (optionnel)
7. **Suppression** : Suppression logique (soft delete) avec conservation des données

#### 4.2.2. Validation conditionnelle

- **Statut draft :** Contenu optionnel
- **Statut pending_review ou published :** Contenu obligatoire, titre requis, au moins une catégorie

#### 4.2.3. Versioning

- **Enregistrement :** À chaque modification (article_versions)
- **Conservation :** Historique complet avec timestamps
- **Consultation :** Accès aux versions précédentes via API (authentifié)
- **Scope :** Minimal (pas de comparaison côté front)

### 4.3. Gestion des utilisateurs

#### 4.3.1. Opérations réservées aux administrateurs

- ✅ Création de nouveaux comptes
- ✅ Modification des rôles et permissions
- ✅ Suppression de comptes
- ✅ Suivi de l'activité

#### 4.3.2. Protections essentielles

- ✅ Prévention de l'auto-suppression d'admin (l'admin ne peut pas se supprimer lui-même)
- ✅ Unicité de l'email en base de données
- ✅ Validation du format d'email
- ✅ Mot de passe temporaire à la création d'un utilisateur

#### 4.3.3. Données collectées

Minimales et justifiées :

- Email (identifiant unique)
- Prénom et nom (affichage)
- Mot de passe (authentification)
- Rôle (permissions)

### 4.4. Gestion des catégories et tags

#### 4.4.1. Gestion générale

- **Création :** Admin / Éditeur
- **Modification :** Admin / Éditeur
- **Suppression :** Admin / Éditeur avec gestion des relations N:N
- **Slug :** Généré automatiquement, unique et URL-friendly

#### 4.4.2. Relations

- Relations N:N avec les articles via tables de liaison
- Suppression sans impact sur les articles (cascade optionnelle)
- Possibilité d'associer plusieurs catégories et tags à un article

### 4.5. Gestion des médias

#### 4.5.1. Upload et validation

| Type de fichier | Extensions autorisées            | Taille max | Utilisation          |
| --------------- | -------------------------------- | ---------- | -------------------- |
| Image           | `.jpg, .jpeg, .png, .webp, .gif` | 10 MB      | Articles, à la une   |
| Vidéo           | `.mp4, .webm, .mov`              | 500 MB     | Contenus multimédias |
| Audio           | `.mp3, .wav, .m4a`               | 50 MB      | Podcasts, clips      |
| Document        | `.pdf, .docx, .xlsx`             | 50 MB      | Ressources           |

#### 4.5.2. Sécurité des uploads

- ✅ Validation MIME type stricte
- ✅ Noms de fichiers uniques (uuid_timestamp.extension)
- ✅ Stockage en `/uploads/` avec `.htaccess` empêchant exécution PHP
- ✅ Vérification intégrité via checksums MD5/SHA256

#### 4.5.3. Gestion des accès

- **Propriétaire :** Peut voir et gérer ses propres fichiers
- **Admin :** Peut voir et gérer tous les fichiers
- **Autres :** Lecture seule via associations d'articles

### 4.6. Interface d'administration

#### 4.6.1. Principes UX/UI

- ✅ Responsive (mobile 320px → desktop 1400px)
- ✅ Accessibilité WCAG 2.1 Level AA
- ✅ Feedback immédiat sur les actions
- ✅ Navigation intuitive et cohérente
- ✅ Design cohérent avec charte graphique

#### 4.6.2. Composants essentiels

- Navigation principale latérale (collapse sur mobile)
- Breadcrumb de localisation
- Notifications toast (succès/erreur/warning)
- Modales de confirmation pour actions destructrices
- Formulaires avec validation côté client + serveur

### 4.7. API publique

#### 4.7.1. Endpoints articles

```
GET /api/v1/articles
  Paramètres: page, limit, category_id, tag_id, search, sort
  Réponse: [{id, title, slug, excerpt, image, status, published_at, author}]

GET /api/v1/articles/:id
  Réponse: {id, title, slug, content, excerpt, categories, tags, author, published_at, versions_count}

GET /api/v1/articles/slug/:slug
  Réponse: (identique à /articles/:id)

GET /api/v1/articles/:id/categories
  Réponse: [{id, name, slug}]

GET /api/v1/articles/:id/tags
  Réponse: [{id, name, slug}]

GET /api/v1/articles/:id/versions [AUTHENTIFIÉ]
  Réponse: [{id, created_at, updated_by, status}]

GET /api/v1/articles/:id/versions/:versionId [AUTHENTIFIÉ]
  Réponse: {id, title, content, excerpt, created_at, status}
```

#### 4.7.2. Endpoints catégories

```
GET /api/v1/categories
  Réponse: [{id, name, slug, articles_count}]

GET /api/v1/categories/:id
  Réponse: {id, name, slug, description, articles_count}

GET /api/v1/categories/:id/articles
  Réponse: [{id, title, slug, excerpt, published_at}]
```

#### 4.7.3. Endpoints tags

```
GET /api/v1/tags
  Réponse: [{id, name, slug, articles_count}]

GET /api/v1/tags/:id
  Réponse: {id, name, slug, description, articles_count}

GET /api/v1/tags/:id/articles
  Réponse: [{id, title, slug, excerpt, published_at}]
```

#### 4.7.4. Endpoints authentification

```
POST /api/v1/auth/login
  Body: {email, password}
  Réponse: {token, user: {id, email, role}}

GET|POST /api/v1/auth/logout
  Réponse: {success: true}

GET /api/v1/auth/profile [AUTHENTIFIÉ]
  Réponse: {id, email, firstName, lastName, role, created_at}
```

#### 4.7.5. Endpoints utilisateurs [ADMIN UNIQUEMENT]

```
GET /api/v1/users
  Paramètres: page, limit, role
  Réponse: [{id, email, firstName, lastName, role, created_at}]

GET /api/v1/users/:id
  Réponse: {id, email, firstName, lastName, role, created_at, articles_count}

POST /api/v1/users
  Body: {email, firstName, lastName, role}
  Réponse: {id, email, firstName, lastName, role} [201]

PATCH /api/v1/users/:id
  Body: {firstName?, lastName?, role?, password?}
  Réponse: {id, email, firstName, lastName, role} [200]

DELETE /api/v1/users/:id
  Réponse: {success: true} [204]
```

#### 4.7.6. Format de réponse

```json
{
  "success": true,
  "status": 200,
  "data": {...},
  "message": "Texte optionnel",
  "errors": null
}
```

#### 4.7.7. Codes HTTP utilisés

| Code | Signification | Contexte              |
| ---- | ------------- | --------------------- |
| 200  | OK            | Requête réussie       |
| 201  | Created       | Ressource créée       |
| 204  | No Content    | Suppression réussie   |
| 400  | Bad Request   | Validation échouée    |
| 401  | Unauthorized  | Non authentifié       |
| 403  | Forbidden     | Non autorisé          |
| 404  | Not Found     | Ressource inexistante |
| 500  | Server Error  | Erreur système        |

---

## 5. EXIGENCES TECHNIQUES

### 5.1. Stack technologique imposée

#### Backend

- **Langage :** PHP 8.4+ (strict types)
- **Serveur :** Apache 2.4+ avec module rewrite
- **Paradigme :** Programmation orientée objet pure (pas de code procédural)
- **Framework :** Framework maison custom (aucune dépendance Composer `require`)
- **Autoloading :** PSR-4 (namespaces et conventions de fichiers)
- **Architecture :** MVC simplifiée (Modèles, Vues, Contrôleurs)

#### Base de données

- **SGBDR :** PostgreSQL 16+
- **Accès :** PDO avec prepared statements (protection SQL injection)
- **Gestion initiale :** Scripts SQL dans `database/init/`
- **Relations :** N:N via tables de liaison explicites
- **Transactions :** Où nécessaire pour intégrité des données

#### Frontend

- **Framework CSS :** Framework SASS maison (pas de Bootstrap/Tailwind)
- **Architecture :**
  - `assets/css/components/` : Composants réutilisables
  - `assets/css/partials/` : Variables, utilities, globals
  - `assets/css/vendor/` : Dépendances externes (modern-normalize)
- **Responsive design :** Mobile-first, breakpoints (sm, md, lg, xl, xxl)
- **Compilation :** Dart Sass → CSS final

#### Conteneurisation

- **Orchestration :** Docker Compose 3.8+
- **Images :**
  - PHP 8.4-apache avec Dockerfile personnalisé
  - PostgreSQL 16 (image officielle)
- **Volumes :** Persistance données (mysql/, app/)
- **Configuration :** `php.ini` personnalisée, `.env` pour secrets
- **Ports :** 80 (web) et 5432 (DB) par défaut

#### Versionnement

- **VCS :** Git via GitHub
- **Commits :** Messages clairs (feat:, fix:, chore:, docs:, style:)
- **Branches :** feature/, fix/, chore/ + main/develop
- **PR :** Code review avant merge

### 5.2. Structure du projet

```
projet-semst/
├── app/
│   ├── src/
│   │   ├── Controllers/       # Contrôleurs (namespace: App\Controllers)
│   │   ├── Models/            # Modèles (namespace: App\Models)
│   │   ├── Repositories/      # Accès données (namespace: App\Repositories)
│   │   ├── Services/          # Logique métier (namespace: App\Services)
│   │   └── Middleware/        # Middlewares (namespace: App\Middleware)
│   ├── views/                 # Templates HTML
│   ├── config/
│   │   ├── routes.json        # Configuration des routes
│   │   └── app.php            # Configuration app
│   ├── bin/                   # Scripts exécutables
│   └── composer.json          # Manifest (require: vide)
├── assets/
│   └── css/                   # Sources SASS
├── database/
│   └── init/                  # Scripts SQL d'initialisation
├── docker/                    # Configuration Docker
├── logs/                      # Fichiers logs
├── public/
│   ├── index.php              # Point d'entrée
│   └── .htaccess              # Rewrite rules
├── uploads/                   # Fichiers uploadés
├── Dockerfile                 # Image PHP 8.4-apache
├── docker-compose.yml         # Orchestration
└── README.md                  # Documentation
```

### 5.3. Normes de codage

#### Conventions PHP

- ✅ Namespaces obligatoires (PSR-4)
- ✅ Noms de classe : PascalCase
- ✅ Noms de fonction/méthode : camelCase
- ✅ Noms de variable : camelCase
- ✅ Constantes : UPPER_SNAKE_CASE
- ✅ 4 espaces pour l'indentation
- ✅ Type hints obligatoires sur tous les paramètres
- ✅ Return types obligatoires

#### Commentaires

- Classes : `/** @description */`
- Méthodes complexes : Bloc de commentaires explicatif
- Code métier : Commentaires sur sections critiques
- TODO/FIXME : Marqueurs explicites si besoin

### 5.4. Performance

| Métrique                    | Cible         |
| --------------------------- | ------------- |
| Temps réponse API           | < 200ms (p95) |
| Temps chargement page admin | < 1s          |
| Taux d'erreur               | < 0.1%        |
| Disponibilité               | 99.5%         |

---

## 6. EXIGENCES DE SÉCURITÉ

### 6.1. Authentification et autorisation

#### 6.1.1. Hachage des mots de passe

- ✅ Algorithme : bcrypt via `password_hash(PASSWORD_BCRYPT, ['cost' => 12])`
- ✅ Vérification : `password_verify()` uniquement
- ✅ Stockage : Jamais en clair, jamais déchiffrable
- ✅ Changement : Lors de la création et modification d'utilisateur

#### 6.1.2. Gestion des sessions

- ✅ Identifiants de session : Régénérés après connexion
- ✅ Durée : Session.lifetime = 3600s (1h) recommandé
- ✅ Sécurité : Cookies HttpOnly, Secure (HTTPS), SameSite=Strict
- ✅ Expiration : Session détruite après logout explicite

#### 6.1.3. Vérification des permissions

- ✅ À chaque action sensible
- ✅ Redirection 403 systématique
- ✅ Logging des tentatives d'accès non autorisé

### 6.2. Protection contre les attaques (OWASP Top 10)

#### 6.2.1. SQL Injection (A03:2021)

- ✅ **PDO prepared statements obligatoires** pour toutes les requêtes
- ✅ Pas de concaténation de SQL
- ✅ Validation de types de paramètres
- ✅ Échappement côté BDD (PDO paramétrisation)
- ✅ Utilisation exclusive de requêtes paramétrées

**Exemple sécurisé :**

```php
$stmt = $connexion->prepare("SELECT * FROM articles WHERE id = ?");
$stmt->execute([$id]);
```

**Exemple DANGEREUX :**

```php
// JAMAIS !
$sql = "SELECT * FROM articles WHERE id = " . $_GET['id'];
$connexion->query($sql);
```

#### 6.2.2. Cross-Site Scripting (XSS) (A07:2021)

- ✅ **Échappement HTML systématique** avec `htmlspecialchars($var, ENT_QUOTES, 'UTF-8')`
- ✅ Validation des entrées utilisateur
- ✅ Content-Security-Policy headers
- ✅ Pas de `eval()` ou dynamique risquée
- ✅ Sanitization des contenus utilisateurs (HTML purifier si nécessaire)

**Exemple sécurisé :**

```php
echo htmlspecialchars($article->getTitle(), ENT_QUOTES, 'UTF-8');
```

#### 6.2.3. Cross-Site Request Forgery (CSRF) (A01:2021)

- ✅ **Tokens CSRF sur tous les formulaires POST/PATCH/DELETE**
- ✅ Vérification du token côté serveur
- ✅ Tokens uniques par session
- ✅ Régénération après utilisation
- ✅ SameSite=Strict sur les cookies

**Implémentation :**

```html
<form method="POST" action="/admin/articles/create">
  <input
    type="hidden"
    name="csrf_token"
    value="<?= $_SESSION['csrf_token'] ?>"
  />
  <!-- autres champs -->
</form>
```

#### 6.2.4. Uploads malveillants (A04:2021)

- ✅ **Validation stricte MIME types** (magic bytes, pas extension)
- ✅ **Limites de taille** par type (voir section 4.5)
- ✅ **Noms de fichiers uniques** (UUID + timestamp)
- ✅ **Stockage sécurisé** en `/uploads/` avec `.htaccess` :
  ```apache
  <FilesMatch "\.php$">
      Deny from all
  </FilesMatch>
  ```
- ✅ **Vérification intégrité** via checksums
- ✅ **Scan antivirus** optionnel (ClamAV)

#### 6.2.5. Authentification faible (A07:2021 - Broken Auth)

- ✅ Pas de authentification par GET
- ✅ Rate limiting sur `/login` (5 tentatives / 15 min)
- ✅ Pas de révélation d'existence utilisateur ("Email inconnu" = message générique)
- ✅ Pas de stockage de mots de passe en session

#### 6.2.6. Erreurs d'exposition sensibles (A01:2021)

- ✅ **Messages d'erreur génériques** en production
- ✅ **Stack trace cachée** en production (log seulement)
- ✅ **Pas d'exposition** d'infos système (version PHP, etc)
- ✅ **Headers sécurité** :
  ```
  X-Frame-Options: DENY
  X-Content-Type-Options: nosniff
  X-XSS-Protection: 1; mode=block
  ```

### 6.3. Sécurité des données

#### 6.3.1. Minimisation des données

- ✅ Collecte justifiée : email, prénom, nom uniquement
- ✅ Pas de stockage de données sensibles non-nécessaires
- ✅ Droit à l'oubli : suppression complète des données utilisateur

#### 6.3.2. Accès restreint

- ✅ Données visibles selon les rôles (admin > éditeur > auteur)
- ✅ Utilisateurs ne voient que leurs propres données (sauf admin)
- ✅ Auditing des accès sensibles

#### 6.3.3. Sécurité de la base de données

- ✅ Credentials sécurisés (fichier `.env` non versionné)
- ✅ Accès BD restreint au conteneur PHP
- ✅ Sauvegarde régulière recommandée
- ✅ Pas de backup avec données sensibles non chiffrées

---

## 7. CONTRAINTES ET LIMITES

### 7.1. Contraintes techniques

| Contrainte                   | Impact                      | Justification         |
| ---------------------------- | --------------------------- | --------------------- |
| PHP orienté objet uniquement | Pas de code procédural      | Respect paradigme POO |
| Framework maison             | Pas de dépendances externes | Pédagogie + maîtrise  |
| PostgreSQL uniquement        | Pas de MySQL/SQLite         | Standardisation BDD   |
| Docker obligatoire           | Conteneurisation totale     | Reproducibilité       |
| Git obligatoire              | Versionning requis          | Traçabilité           |

### 7.2. Contraintes fonctionnelles

| Limitation                 | Raison                                      |
| -------------------------- | ------------------------------------------- |
| Pas de front-office public | Scope : back-office uniquement (CMS)        |
| API en lecture seule       | Sécurité : pas de modification externe      |
| Versioning minimal         | Complexité : pas de diff côté front         |
| Pas de recherche fulltext  | Performance : simple filtre texte suffisant |
| Pas de cache Redis         | Scope : cache simple en session PHP         |
| Pas de multi-langue        | Scope : français uniquement                 |

### 7.3. Contraintes de temps

- **Durée totale :** 1 semestre
- **Échéance :** Date définie par Decode
- **Jalons critiques :**
  - Sprint 1-2 : Setup + Framework
  - Sprint 3-4 : Gestion articles
  - Sprint 5-6 : Interface admin
  - Sprint 7-8 : API + Tests
  - Semaine 9 : Documentation + Déploiement

### 7.4. Contraintes de ressources

| Ressource      | Limitation                     |
| -------------- | ------------------------------ |
| Équipe         | Étudiants seulement            |
| Budget         | Gratuit (GitHub, Docker Hub)   |
| Infrastructure | Locale + serveur simple        |
| Outils         | Gratuits (VSCode, Git, Docker) |

---

## 8. CRITÈRES D'ACCEPTATION

### 8.1. Critères fonctionnels

#### Authentification

- [x] Utilisateur peut se connecter avec email + mot de passe
- [x] Utilisateur non authentifié redirigé vers login
- [x] Utilisateur peut se déconnecter
- [x] Mots de passe hachés (bcrypt) et non réversibles
- [x] Session régénérée après connexion
- [x] Rate limiting sur la page de connexion

#### Gestion des articles

- [x] Admin : CRUD complet sur tous les articles
- [x] Éditeur : CRUD complet sur tous les articles
- [x] Auteur : Création, édition propre, suppression propre uniquement
- [x] Auteur : Publication directe désactivée (validation éditeur nécessaire)
- [x] Contenu obligatoire seulement si `status = published`
- [x] Association multiple catégories/tags possible
- [x] Image à la une optionnelle
- [x] Versioning fonctionnel (historique enregistré)

#### Gestion des utilisateurs

- [x] Admin : CRUD complet des utilisateurs
- [x] Admin : Attribution/modification des rôles
- [x] Admin : Impossible de s'auto-supprimer
- [x] Email unique en base
- [x] Mot de passe temporaire à la création

#### Gestion catégories/tags

- [x] Admin/Éditeur : Création, édition, suppression
- [x] Auteur : Accès en lecture seule
- [x] Slugs uniques et générés automatiquement
- [x] Relations N:N avec articles

#### Gestion médias

- [x] Tous : Upload de fichiers avec validation
- [x] MIME types validés (magic bytes)
- [x] Tailles limitées par type
- [x] Noms de fichiers uniques
- [x] Stockage sécurisé (pas exécution PHP)
- [x] Métadonnées (titre, description, alt-text)

#### Interface d'administration

- [x] Dashboard synthétique accessible
- [x] Navigation latérale responsive
- [x] Formulaires avec validation côté client + serveur
- [x] Notifications feedback (toast)
- [x] Modales de confirmation pour suppressions
- [x] Protection CSRF sur tous les formulaires

#### API publique

- [x] GET `/api/v1/articles` : Liste articles publiés
- [x] GET `/api/v1/articles/:id` : Détail article
- [x] GET `/api/v1/articles/slug/:slug` : Détail par slug
- [x] Réponses JSON structurées
- [x] Codes HTTP appropriés
- [x] Pagination fonctionnelle

#### Pages d'erreur

- [x] Page 404 personnalisée (design cohérent)
- [x] Page 403 personnalisée (accès interdit)
- [x] Page 500 personnalisée (erreur serveur)
- [x] Liens de retour vers l'accueil

### 8.2. Critères techniques

#### Framework maison

- [x] Routing dynamique fonctionnel
- [x] Système de contrôleurs opérationnel
- [x] Request/Response objets
- [x] Middleware d'authentification
- [x] Respect standards PSR-4

#### Sécurité

- [x] Aucune injection SQL (prepared statements)
- [x] XSS prévenu (htmlspecialchars)
- [x] CSRF protégé (tokens)
- [x] Uploads sécurisés (validation stricte)
- [x] Mots de passe hachés (bcrypt)
- [x] Headers sécurité HTTP

#### Code

- [x] Code orienté objet (100% POO)
- [x] Conventions de nommage respectées
- [x] Commentaires dans le code
- [x] Type hints obligatoires
- [x] Structure modulaire

#### Docker

- [x] Dockerfile fonctionnel PHP 8.4-apache
- [x] docker-compose.yml avec services
- [x] Volumes persistés
- [x] Configuration php.ini
- [x] Démarrage simplifié (`docker-compose up`)

### 8.3. Critères de qualité

#### Documentation

- [x] README avec instructions d'installation
- [x] Documentation technique du framework
- [x] Documentation API complète
- [x] Commentaires pertinents dans le code
- [x] Architecture documentée

#### Interface utilisateur

- [x] Responsive (320px → 1400px)
- [x] Navigation intuitive
- [x] Messages d'erreur clairs
- [x] Design cohérent sur tout le site
- [x] Accessibilité minimum WCAG 2.1 Level A

#### Performance

- [x] Temps réponse < 2s (p95)
- [x] Pas de requêtes N+1 (eager loading)
- [x] Optimisation images
- [x] CSS minifié en production

#### Maintenance

- [x] Code facilement modifiable
- [x] Bugs correctibles rapidement
- [x] Logs pertinents activés
- [x] Pas de warnings PHP

---

## 9. LIVRABLES ATTENDUS

### 9.1. Livrables techniques

#### Code source

- [x] **Repository GitHub** complet avec historique de commits propre
- [x] **Framework maison** (`app/src/*`)
- [x] **Code CMS** (Controllers, Models, Views)
- [x] **Scripts SQL** d'initialisation (`database/init/`)
- [x] **Configuration Docker** (Dockerfile, docker-compose.yml)
- [x] **Configuration PHP** (php.ini)
- [x] **Assets CSS** (SASS compilé)

#### Documentation technique

- [x] **README.md** complet :
  - Prérequis (Docker, PHP 8.4, etc)
  - Instructions installation
  - Commandes de démarrage
  - Identifiants par défaut
  - Architecture du projet
  - Convention de code
- [x] **Documentation API** (`docs/API.md`)
- [x] **Documentation Framework** (`docs/FRAMEWORK.md`)
- [x] **Commentaires dans le code** (blocs de description)
- [x] **Schéma BDD** (image/document)

#### Application fonctionnelle

- [x] Application déployée et opérationnelle
- [x] Base de données initialisée avec données de test
- [x] Comptes utilisateurs pré-créés (admin, éditeur, auteur)
- [x] Accès au back-office fonctionnel
- [x] API publique opérationnelle

### 9.2. Livrables de management

#### Documentation gestion de projet

- [x] **Planning** des sprints et jalons
- [x] **Suivi des tâches** (GitHub Issues + Project)
- [x] **Matrice des risques** identifiés
- [x] **Décisions techniques** documentées
- [x] **Journal de bord** d'équipe (réunions, décisions)

#### Matériel de présentation

- [x] **Support de présentation** (diapositives) :
  - Contexte et objectifs
  - Choix techniques justifiés
  - Architecture du système
  - Démo fonctionnelle
  - Métriques de qualité
  - Lessons learned
- [x] **Démonstration live** du CMS (back-office + API)
- [x] **Réponses aux questions** du jury

---

## 10. MÉTRIQUES DE QUALITÉ

### 10.1. Métriques techniques

| Métrique           | Cible   | Mesure                  |
| ------------------ | ------- | ----------------------- |
| Couverture de code | 60%+    | PHPUnit + PHPQA         |
| Violations PSR-12  | 0       | PHP_CodeSniffer         |
| Warnings PHP       | 0       | error_reporting = E_ALL |
| Temps réponse API  | < 200ms | Monitoring              |
| Taux d'erreur      | < 0.1%  | Logs + monitoring       |
| Disponibilité      | 99%+    | Uptime                  |

### 10.2. Métriques fonctionnelles

| Critère                            | Mesure                    |
| ---------------------------------- | ------------------------- |
| Tous les endpoints API testés      | 100% fonctionnels         |
| Tous les rôles/permissions validés | Pas de brèche de sécurité |
| Tous les cas d'erreur gérés        | Messages cohérents        |
| Performance acceptable             | < 2s de charge            |

### 10.3. Métriques de projet

| KPI                    | Cible               |
| ---------------------- | ------------------- |
| Avancement en ligne    | 100% des sprints    |
| Qualité livrables      | Validation client   |
| Communication d'équipe | Réunions régulières |
| Documentation          | Complète et à jour  |

---

## APPENDICE : GLOSSAIRE

| Terme          | Définition                                                     |
| -------------- | -------------------------------------------------------------- |
| **CMS**        | Content Management System – Système de gestion de contenu      |
| **API**        | Application Programming Interface – Interface de programmation |
| **REST**       | Representational State Transfer – Architecture d'API           |
| **OWASP**      | Open Web Application Security Project – Référence sécurité web |
| **CSRF**       | Cross-Site Request Forgery – Attaque de requête cross-site     |
| **XSS**        | Cross-Site Scripting – Injection de scripts malveillants       |
| **JWT**        | JSON Web Token – Token d'authentification (non utilisé ici)    |
| **PDO**        | PHP Data Objects – Abstraction accès bases de données          |
| **PSR-4**      | PHP Standards Recommendation 4 – Standard autoloading PHP      |
| **OOP**        | Object-Oriented Programming – Programmation orientée objet     |
| **MVC**        | Model-View-Controller – Architecture logicielle                |
| **N:N**        | Relation Many-to-Many entre deux tables                        |
| **Docker**     | Plateforme de conteneurisation                                 |
| **Repository** | Dépôt Git avec code source                                     |

---

**Document validé le :** 25 janvier 2026  
**Version :** 2.0 – Améliorée et professionnalisée  
**Statut :** ✅ Prêt pour production
