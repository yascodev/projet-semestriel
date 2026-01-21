# ✅ Checklist de Déploiement - Gestion des Articles

## 📋 Pré-Déploiement

### Vérification du Code
- [ ] Tous les fichiers sont créés aux bons endroits
- [ ] Pas de fichiers temporaires ou de débogage
- [ ] Code formaté et indenté correctement
- [ ] Pas de `console.log()` ou `var_dump()` oubliés
- [ ] Pas de fichiers `.DS_Store` ou `Thumbs.db`

### Vérification de la Sécurité
- [ ] Tous les inputs sont validés
- [ ] CSRF tokens présents sur tous les formulaires
- [ ] HTML escaping en place avec `htmlspecialchars()`
- [ ] SQL utilise des prepared statements
- [ ] Pas d'authentification en dur (hardcoded)
- [ ] Permissions vérifiées dans chaque controller
- [ ] Pas de secrets exposés (clés API, tokens, etc.)

### Vérification des Performances
- [ ] Indexes de base de données créés
- [ ] Pas de N+1 queries (vérifier les load de relations)
- [ ] Cache des sessions utilisateur
- [ ] Pagination implémentée (max 10 articles par page)

### Vérification de la Compatibilité
- [ ] PHP 8.4+ (conforme au projet)
- [ ] PostgreSQL 16+ (conforme au projet)
- [ ] Navigateurs modernes testés (Chrome, Firefox, Safari)
- [ ] Design responsive (mobile, tablet, desktop)

---

## 🗂️ Fichiers à Vérifier

### Controllers (8 fichiers)
- [ ] `ListArticlesController.php` - OK
- [ ] `FormCreateArticleController.php` - OK
- [ ] `CreateArticleActionController.php` - OK
- [ ] `FormEditArticleController.php` - OK
- [ ] `UpdateArticleController.php` - OK
- [ ] `PublishArticleActionController.php` - OK
- [ ] `ArchiveArticleActionController.php` - OK
- [ ] `DeleteArticleController.php` - OK

### Views (3 fichiers)
- [ ] `articles.html` - OK
- [ ] `create-article.html` - OK
- [ ] `edit-article.html` - OK

### Configuration
- [ ] `routes.json` - 9 routes ajoutées - OK
- [ ] `Request.php` - méthodes getPost/getQuery ajoutées - OK

### Documentation
- [ ] `GESTION_ARTICLES_DOCUMENTATION.md` - OK
- [ ] `GUIDE_TEST_ARTICLES.md` - OK
- [ ] `RESUME_IMPLEMENTATION.md` - OK
- [ ] `DATABASE_SCHEMA.sql` - OK
- [ ] `CHECKLIST_DEPLOYMENT.md` - OK (ce fichier)

---

## 🧪 Tests Avant Déploiement

### Tests Manuels (voir GUIDE_TEST_ARTICLES.md)
- [ ] Créer article en brouillon
- [ ] Créer et publier article
- [ ] Modifier article
- [ ] Publier/Archiver/Republier article
- [ ] Supprimer article
- [ ] Filtrer par statut
- [ ] Paginer résultats
- [ ] Valider formulaires
- [ ] Tester permissions (author/editor/admin)
- [ ] Tester CSRF protection

### Tests de Sécurité
- [ ] Tenter accès sans authentification → Redirection /login
- [ ] Author ne peut pas éditer article d'un autre
- [ ] CSRF token invalide → Rejet
- [ ] SQL injection → Protection par prepared statements
- [ ] XSS (caractères spéciaux) → Echappés

### Tests de Performance
- [ ] Pagination avec 100+ articles
- [ ] Filtrage performant
- [ ] Temps de réponse < 500ms

### Tests de Navigation
- [ ] Tous les liens fonctionnent
- [ ] Boutons directs vers les bonnes actions
- [ ] Messages de redirection clairs

---

## 📊 Rapport de Test (À Compléter)

```
Tester: _________________
Date: _____________________
Version: __________________

Fonctionnalités:
[ ] Création .......................... OK / KO
[ ] Lecture ........................... OK / KO
[ ] Modification ...................... OK / KO
[ ] Suppression ....................... OK / KO
[ ] Filtrage .......................... OK / KO
[ ] Pagination ........................ OK / KO
[ ] Validation ........................ OK / KO
[ ] Permissions ....................... OK / KO
[ ] CSRF Protection ................... OK / KO

Bugs trouvés:
1. _________________________
2. _________________________
3. _________________________

Observations:
_________________________________
_________________________________
_________________________________

Recommandations:
_________________________________
_________________________________

Approbation finale:
[ ] Approuvé pour production
[ ] Approuvé avec restrictions
[ ] À revoir
```

---

## 🚀 Déploiement

### Étape 1: Préparation
```bash
# Vérifier les logs actuels
docker-compose logs --tail=50 app

# Backup de la base de données
docker exec projet-semst-postgres pg_dump -U postgres > backup_$(date +%Y%m%d_%H%M%S).sql
```

### Étape 2: Mise à Jour du Code
```bash
# Naviguer dans le répertoire du projet
cd c:\Users\conta\OneDrive\Bureau\projet-semst

# Vérifier les fichiers modifiés
git status

# Ajouter et committer les changements
git add -A
git commit -m "feat(articles): Ajouter gestion complète des articles au back-office"

# Pousser vers le repository
git push origin main  # ou la branche appropriée
```

### Étape 3: Mise à Jour de la Base de Données (si nécessaire)
```bash
# Si des migrations sont nécessaires, les exécuter
docker exec projet-semst-postgres psql -U postgres < DATABASE_SCHEMA.sql
```

### Étape 4: Redémarrage des Conteneurs
```bash
# Rebuild et restart
docker-compose down
docker-compose up -d --build

# Vérifier les logs
docker-compose logs -f app

# Tester l'accès
# Naviguer sur http://localhost/admin/articles
```

### Étape 5: Tests Post-Déploiement
- [ ] Site accessible
- [ ] Pas d'erreurs 500
- [ ] Connexion fonctionne
- [ ] Page admin/articles charge
- [ ] Créer un article de test
- [ ] Modifier l'article
- [ ] Publier l'article
- [ ] Voir dans la liste
- [ ] Supprimer l'article

---

## 🔄 Rollback Plan (En Cas de Problème)

### Rollback Rapide
```bash
# 1. Restaurer le code précédent
git revert HEAD

# 2. Redémarrer
docker-compose down
docker-compose up -d --build

# 3. Si BD corrompue, restaurer backup
docker exec -i projet-semst-postgres psql -U postgres < backup_YYYYMMDD_HHMMSS.sql
```

### Vérification Après Rollback
- [ ] Site fonctionne
- [ ] Pas d'erreurs
- [ ] Données intactes
- [ ] Tous les services up

---

## 📞 Checklist de Communication

### Avant Déploiement
- [ ] Équipe informée de la date/heure de déploiement
- [ ] Documentation envoyée aux utilisateurs
- [ ] Message sur le dashboard (maintenance prévue)

### Pendant Déploiement
- [ ] Temps d'arrêt minimal
- [ ] Logs monitées en temps réel
- [ ] Équipe disponible pour support

### Après Déploiement
- [ ] Confirmation du succès à l'équipe
- [ ] Utilisateurs notifiés que c'est live
- [ ] Retour d'expérience collecté

---

## 📈 Monitoring Post-Déploiement

### Points à Surveiller
- [ ] Taux d'erreur 500 (< 0.1%)
- [ ] Taux d'erreur 404 (< 0.5%)
- [ ] Performance des pages (< 1s)
- [ ] Utilisation disque (< 80%)
- [ ] Utilisation mémoire (< 80%)
- [ ] Connexions DB (< limite)

### Commandes de Monitoring
```bash
# Voir les logs temps réel
docker-compose logs -f app

# Voir l'utilisation des ressources
docker stats

# Vérifier la santé du conteneur
docker ps
```

### Alertes à Configurer
- [ ] Erreurs 500 supérieures à 1% par heure
- [ ] Disque plein à 90%
- [ ] Mémoire pleine à 90%
- [ ] Base de données inaccessible
- [ ] Conteneur app redémarre plus de 3 fois

---

## 📋 Points de Contrôle Quotidiens (1ère Semaine)

**Jour 1 Post-Déploiement**
- [ ] Pas d'erreurs critiques
- [ ] Utilisateurs peuvent créer articles
- [ ] Performances normales

**Jours 2-3**
- [ ] Pas de bugs signalés
- [ ] Pas d'anomalies de performance
- [ ] Données cohérentes

**Jours 4-7**
- [ ] Système stable
- [ ] Pas de memory leaks
- [ ] Utilisateurs satisfaits

---

## 🎓 Documentation Post-Déploiement

### À Préparer
- [ ] Guide d'utilisation pour les utilisateurs
- [ ] Guide administrateur pour la gestion
- [ ] FAQ des problèmes courants
- [ ] Procédure de backup/restore

### À Distribuer
- [ ] Lien vers la documentation
- [ ] Email de notification
- [ ] Session de formation (optionnel)

---

## ✅ Signature d'Acceptation

```
Préparation: ______ / ______ / ______  [Initialisée par: __________]
Tests: ______ / ______ / ______        [Validée par: __________]
Déploiement: ______ / ______ / ______  [Déployée par: __________]
Vérification: ______ / ______ / ______  [Vérifiée par: __________]

Approbation Production: ☐ OUI ☐ NON
Approuvé par: ______________________ Date: ______________
```

---

## 📝 Notes Supplémentaires

### Éléments à Améliorer à L'Avenir
1. Cache Redis pour les articles fréquemment accédés
2. Full-text search pour rechercher rapidement
3. Bulk edit pour éditer plusieurs articles à la fois
4. Scheduler pour publication programmée
5. Webhooks pour intégration externe
6. API REST complète

### Optimisations à Considérer
1. Lazy loading des images
2. CDN pour les assets statiques
3. Minification des CSS/JS
4. Compression Gzip
5. Cache browser (ETag)

### Améliorations UX
1. Drag & drop pour réordonner
2. Wysiwyg editor pour le contenu
3. Suggestion d'autosave
4. Collaborazione en temps réel
5. Notifications en temps réel

---

**Date de Préparation**: 21/01/2026  
**Version**: 1.0  
**Status**: 🟢 Prêt pour déploiement
