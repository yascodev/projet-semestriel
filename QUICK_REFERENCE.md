# 🎯 Quick Reference - Gestion des Articles

## 🚀 Démarrage Rapide (< 2 min)

### Accéder au Back-Office
```
1. Aller sur http://localhost/login
2. Entrer identifiants
3. Aller sur http://localhost/admin/articles
```

### Créer un Article
```
1. Cliquer "+ Créer un article"
2. Remplir: Titre, Extrait, Contenu
3. Cliquer "Créer et publier" ou "Enregistrer en brouillon"
```

### Modifier un Article
```
1. Cliquer "✎ Éditer" sur l'article
2. Modifier les champs
3. Cliquer "💾 Enregistrer les modifications"
```

### Publier un Brouillon
```
1. Trouver l'article en brouillon
2. Cliquer "✓ Publier"
```

### Supprimer un Article
```
1. Cliquer "🗑️ Supprimer"
2. Confirmer la suppression
```

---

## 🗂️ Structure (Vue d'ensemble)

```
📂 Projet
 ├─ 🎮 Controllers (8)
 │  ├─ ListArticles         ← Affiche la liste
 │  ├─ FormCreate          ← Formulaire création
 │  ├─ CreateAction        ← Traite création
 │  ├─ FormEdit            ← Formulaire édition
 │  ├─ UpdateArticle       ← Traite édition
 │  ├─ PublishArticle      ← Publie
 │  ├─ ArchiveArticle      ← Archive
 │  └─ DeleteArticle       ← Supprime
 │
 ├─ 👀 Views (3)
 │  ├─ articles.html       ← Liste
 │  ├─ create-article.html ← Formulaire création
 │  └─ edit-article.html   ← Formulaire édition
 │
 ├─ 🗄️ Database
 │  ├─ articles table
 │  ├─ article_category (M:N)
 │  └─ article_tag (M:N)
 │
 └─ 🛣️ Routes (9)
    ├─ GET    /admin/articles
    ├─ GET    /admin/articles/create
    ├─ POST   /admin/articles/create
    ├─ GET    /admin/articles/:id/edit
    ├─ POST   /admin/articles/:id/update
    ├─ POST   /admin/articles/:id/publish
    ├─ POST   /admin/articles/:id/archive
    └─ POST   /admin/articles/:id/delete
```

---

## 📋 Statuts d'Article

| Statut | Icône | Description | Actions |
|--------|-------|-------------|---------|
| Draft | 📝 | Brouillon | Éditer, Publier, Supprimer |
| Published | ✓ | Publié | Éditer, Archiver, Supprimer |
| Archived | 📦 | Archivé | Éditer, Republier, Supprimer |

---

## 🔑 Rôles et Permissions

| Rôle | Voir Tous | Créer | Éditer Siens | Éditer Tous | Admin |
|------|-----------|-------|-------------|-------------|--------|
| Author | ❌ | ✅ | ✅ | ❌ | ❌ |
| Editor | ❌ | ✅ | ✅ | ❌ | ❌ |
| Admin | ✅ | ✅ | ✅ | ✅ | ✅ |

---

## ✅ Checklist Rapide

### Création d'Article
- [ ] Titre (3-255 caractères)
- [ ] Extrait (10-500 caractères)
- [ ] Contenu (10+ caractères)
- [ ] Catégories (optionnel)
- [ ] Tags (optionnel)
- [ ] Action (Brouillon / Publier)

### Édition d'Article
- [ ] Titre modifié ✓
- [ ] Contenu modifié ✓
- [ ] Statut vérifié ✓
- [ ] Catégories/Tags mis à jour ✓

### Avant Publication
- [ ] Relire le contenu
- [ ] Vérifier les catégories
- [ ] Ajouter les tags
- [ ] Vérifier les perms

---

## 🔐 Sécurité

### CSRF Protection
✅ Tous les formulaires protégés par token CSRF

### Authentification
✅ Requise pour tous les endpoints

### Validation
✅ Client-side (JavaScript) + Server-side (PHP)

### Permissions
✅ Vérifiées avant chaque action

---

## 🛣️ URLs Importantes

```
http://localhost/admin                    ← Dashboard
http://localhost/admin/articles           ← Liste articles
http://localhost/admin/articles/create    ← Créer article
http://localhost/admin/articles/:id/edit  ← Éditer article
```

---

## 📊 Filters & Pagination

### Filtrer par Statut
```
?status=draft      ← Brouillons
?status=published  ← Publiés
?status=archived   ← Archivés
```

### Paginer
```
?page=1  ← Première page
?page=2  ← Deuxième page
```

### Combiner
```
?status=draft&page=2  ← Brouillons page 2
```

---

## 🧪 Cas de Test Critiques

| Test | Étapes | Résultat Expected |
|------|--------|-------------------|
| Créer | 1. Form 2. Submit | ✅ Créé et visible |
| Éditer | 1. Edit 2. Change 3. Save | ✅ Modifié |
| Publier | 1. Click Publish | ✅ Statut = Published |
| Archiver | 1. Click Archive | ✅ Statut = Archived |
| Supprimer | 1. Click Delete 2. Confirm | ✅ Supprimé |
| Permissions | 1. Try edit other's | ✅ Refused |
| CSRF | 1. Bad token | ✅ Rejected |

---

## 🐛 Troubleshooting Rapide

| Problème | Solution |
|----------|----------|
| "Permission refusée" | Vérifier le rôle / l'auteur de l'article |
| "Erreur CSRF" | Actualiser la page et recommencer |
| Article ne s'affiche pas | Vérifier que le statut est "published" |
| Erreur 404 | Vérifier que le route existe dans routes.json |
| Erreur 500 | Vérifier les logs Docker : `docker-compose logs app` |
| Base de données vide | Créer les données de test |

---

## 💾 Commandes Utiles

### Docker
```bash
docker-compose up -d --build       ← Démarrer
docker-compose logs -f app         ← Voir logs
docker-compose down                ← Arrêter
```

### Base de Données
```bash
docker exec -it projet-semst-postgres psql -U postgres
# \dt              - Voir toutes les tables
# SELECT * FROM articles;  - Lister articles
```

### Test Articles
```bash
# Lancer les tests
GUIDE_TEST_ARTICLES.md
```

---

## 📚 Documentation

| Document | Durée | Type |
|----------|-------|------|
| RESUME_IMPLEMENTATION.md | 10 min | 📋 Vue d'ensemble |
| GESTION_ARTICLES_DOCUMENTATION.md | 20 min | 📄 Technique |
| DEVELOPER_GUIDE.md | 30 min | 👨‍💻 Développeur |
| GUIDE_TEST_ARTICLES.md | 20 min | 🧪 Test |
| CHECKLIST_DEPLOYMENT.md | 20 min | ✅ Déploiement |
| DATABASE_SCHEMA.sql | 10 min | 🗄️ BD |
| INDEX_DOCUMENTATION.md | 5 min | 📑 Index |

---

## 🎯 Flow Visuel

### Création Article
```
Form ─┬─ Validation JS ─── Erreur ? ──┐
      └─ Submit ─ Validation PHP ─┬─ OK? ─┬─ Create DB ─ Redirection
                                   │      └─ Error
                                   └─ Error
```

### Liste Articles
```
GET /admin/articles ─ Auth? ─ Admin? ─┬─ Voir tous
                                       └─ Voir les siens
                                             │
                                          Filtrer
                                             │
                                          Paginer
                                             │
                                          Render View
```

---

## 📞 Support Rapide

### Développeur
👉 DEVELOPER_GUIDE.md → Section "Débogage"

### Testeur
👉 GUIDE_TEST_ARTICLES.md → Section "FAQ"

### DevOps
👉 CHECKLIST_DEPLOYMENT.md → Section "Logs"

### Utilisateur
👉 Voir la documentation simplifiée (à créer)

---

## ⚡ Optimisations de Produit

| Feature | Status | Note |
|---------|--------|------|
| Search | ⏳ À faire | Recherche par titre |
| Bulk Edit | ⏳ À faire | Éditer plusieurs articles |
| Scheduler | ⏳ À faire | Publier à une date |
| Analytics | ⏳ À faire | Statistiques |
| Notifications | ⏳ À faire | Alerts temps réel |
| API | ✅ Existant | Articles/Categories/Tags |

---

## 🎓 Ressources

- 🔗 [OWASP Security](https://www.owasp.org)
- 🔗 [PHP Security](https://www.php.net/manual/en/security.php)
- 🔗 [PostgreSQL Docs](https://www.postgresql.org/docs/)
- 🔗 [Docker Docs](https://docs.docker.com/)

---

## 📅 Timeline Typique

| Jour | Tâche | Durée |
|------|-------|-------|
| 1 | Lire doc | 2h |
| 2 | Tester | 3h |
| 3 | Correction | 2h |
| 4-5 | Validation | 4h |
| 6+ | Production | ∞ |

---

## 🏁 Prêt à Commencer?

```
Niveau   | Étape Suivante
---------|----------------------------------
Nouveau  | Lire RESUME_IMPLEMENTATION.md
Dev      | Lire DEVELOPER_GUIDE.md
Testeur  | Lire GUIDE_TEST_ARTICLES.md
DevOps   | Lire CHECKLIST_DEPLOYMENT.md
Admin    | Lire INDEX_DOCUMENTATION.md
```

---

**Créé le**: 21/01/2026  
**Dernière mise à jour**: 21/01/2026  
**Version**: 1.0  
**Status**: ✅ Prêt
