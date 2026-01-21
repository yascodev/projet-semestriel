# 🎯 Gestion des Articles - Guide d'Installation et Test

## Installation Rapide

### 1. Vérifier que le projet est démarré
```bash
docker-compose up -d --build
```

### 2. Accéder à l'application
- Frontend: http://localhost
- Back-office: http://localhost/admin

### 3. S'authentifier
1. Allez sur http://localhost/login
2. Entrez vos identifiants (créez un utilisateur si nécessaire)

---

## 🧪 Plan de Test

### Test 1: Afficher la liste des articles
**URL** : `http://localhost/admin/articles`
**Résultat attendu** :
- [ ] Page chargée correctement
- [ ] Tableau affiché
- [ ] Bouton "+ Créer un article" visible
- [ ] Filtre par statut fonctionnel

### Test 2: Créer un article en brouillon
**Étapes** :
1. Cliquez sur "+ Créer un article"
2. Remplissez le formulaire :
   - Titre: "Mon premier article"
   - Extrait: "Ceci est un extrait d'exemple pour le test"
   - Contenu: "Voici le contenu complet de l'article avec du texte"
3. Laissez les catégories/tags vides
4. Cliquez sur "💾 Enregistrer en brouillon"

**Résultat attendu** :
- [ ] Redirection vers `/admin/articles?success=created`
- [ ] Message de succès affiché
- [ ] Article visible dans la liste avec statut "Brouillon"

### Test 3: Créer un article et le publier immédiatement
**Étapes** :
1. Cliquez sur "+ Créer un article"
2. Remplissez le formulaire
3. Cliquez sur "✓ Créer et publier"

**Résultat attendu** :
- [ ] Article créé avec statut "Publié"
- [ ] Date de publication définie

### Test 4: Modifier un article
**Étapes** :
1. Dans la liste, cliquez sur "✎ Éditer" d'un article
2. Modifiez le titre : "Mon article modifié"
3. Cliquez sur "💾 Enregistrer les modifications"

**Résultat attendu** :
- [ ] Article mis à jour
- [ ] Slug régénéré si title a changé
- [ ] Message de succès

### Test 5: Publier un brouillon
**Étapes** :
1. Trouvez un article en "Brouillon"
2. Cliquez sur "✓ Publier"
3. Confirmez l'action

**Résultat attendu** :
- [ ] Statut passe à "Publié"
- [ ] Date de publication définie
- [ ] Message de succès

### Test 6: Archiver un article publié
**Étapes** :
1. Trouvez un article "Publié"
2. Cliquez sur "📦 Archiver"

**Résultat attendu** :
- [ ] Statut passe à "Archivé"

### Test 7: Republier un article archivé
**Étapes** :
1. Trouvez un article "Archivé"
2. Cliquez sur "✓ Republier"

**Résultat attendu** :
- [ ] Statut passe à "Publié"

### Test 8: Supprimer un article
**Étapes** :
1. Cliquez sur "🗑️ Supprimer" d'un article
2. Confirmez la suppression

**Résultat attendu** :
- [ ] Dialog de confirmation affiché
- [ ] Article supprimé de la liste
- [ ] Message de succès

### Test 9: Filtrer par statut
**Étapes** :
1. Sélectionnez "Brouillons" dans le dropdown
2. Cliquez sur "Envoyer" ou attendez (auto-submit)

**Résultat attendu** :
- [ ] Seuls les brouillons affichés
- [ ] URL contient `?status=draft`
- [ ] Pagination mise à jour

### Test 10: Tester la validation
**Étapes** :
1. Allez sur le formulaire de création
2. Tentez de valider sans remplir les champs
3. Tentez un titre avec moins de 3 caractères
4. Tentez un contenu avec moins de 10 caractères

**Résultat attendu** :
- [ ] Messages d'erreur affichés
- [ ] Formulaire non validé
- [ ] Page scroll vers le top

### Test 11: Tester les permissions (Author vs Admin)
**Avec un account Author** :
1. Créez un article
2. Vérifiez que vous pouvez l'éditer/supprimer
3. Tentez d'éditer un article d'un autre auteur
   - URL: `/admin/articles/{OTHER_ID}/edit`

**Résultat attendu** :
- [ ] Permission refusée
- [ ] Redirection vers liste avec message d'erreur

### Test 12: Tester CSRF
**Étapes** :
1. Ouvrez la console dev (F12)
2. Allez sur un formulaire
3. Modifiez le token CSRF en console avant envoi
4. Tentez de soumettre

**Résultat attendu** :
- [ ] Erreur CSRF détectée
- [ ] Formulaire rejeté

### Test 13: Pagination
**Étapes** :
1. Créez au moins 11 articles
2. Allez sur `/admin/articles`
3. Naviguez entre les pages

**Résultat attendu** :
- [ ] 10 articles par page max
- [ ] Lien "Suivante" fonctionnel
- [ ] Lien "Dernière" correct

### Test 14: Caractères spéciaux
**Étapes** :
1. Créez un article avec titre contenant : `<script>alert('test')</script>`
2. Affichage dans le tableau

**Résultat attendu** :
- [ ] HTML échappé et affiché en texte
- [ ] Aucun script exécuté
- [ ] Affichage: `<script>alert('test')</script>`

---

## 📋 Checklist de Validation

**Fonctionnalités** :
- [ ] Créer article en brouillon
- [ ] Créer et publier article
- [ ] Modifier article
- [ ] Publier brouillon
- [ ] Archiver article
- [ ] Republier article archivé
- [ ] Supprimer article
- [ ] Filtrer par statut
- [ ] Paginer les résultats
- [ ] Validation côté client
- [ ] Validation côté serveur

**Sécurité** :
- [ ] CSRF protection active
- [ ] Permissions author/editor/admin respectées
- [ ] HTML escaping fonctionnel
- [ ] Authentification requise

**UX** :
- [ ] Messages de feedback clairs
- [ ] Formulaires responsive
- [ ] Navigation intuitive
- [ ] Confirmations avant suppression

---

## 🐛 Logs et Debugging

### Voir les logs Docker
```bash
docker-compose logs -f app
```

### Logs PHP
```bash
docker exec projet-semst-app tail -f /var/log/apache2/error.log
```

### Vérifier les sessions
```bash
# Dans le conteneur
docker exec -it projet-semst-app bash
# Puis:
ps aux | grep php
```

---

## 💾 Données de Test

### Créer un utilisateur test
```sql
INSERT INTO "user" (email, password, role, created_at, updated_at) 
VALUES (
    'test@example.com',
    '$2y$10$...', -- hash de 'password' avec PASSWORD_BCRYPT
    'author',
    NOW(),
    NOW()
);
```

### Créer des catégories
```sql
INSERT INTO category (name, slug, description, created_at, updated_at) 
VALUES 
    ('Tech', 'tech', 'Articles sur la technologie', NOW(), NOW()),
    ('Lifestyle', 'lifestyle', 'Style de vie', NOW(), NOW());
```

### Créer des tags
```sql
INSERT INTO tags (name, slug, description, created_at, updated_at) 
VALUES 
    ('JavaScript', 'javascript', 'Langage de programmation', NOW(), NOW()),
    ('React', 'react', 'Framework JavaScript', NOW(), NOW());
```

---

## ❓ FAQ

### Q: Comment réinitialiser les données?
A: 
```bash
docker-compose down -v
docker-compose up -d --build
```

### Q: Comment accéder à PostgreSQL?
A:
```bash
docker exec -it projet-semst-postgres psql -U postgres
```

### Q: Où sont les fichiers téléchargés?
A: Le projet ne gère pas d'uploads actuellement. À implémenter.

### Q: Comment tester avec plusieurs utilisateurs?
A: Créez plusieurs utilisateurs avec des rôles différents en base de données.

---

## 📞 Support

Pour toute question ou problème :
1. Vérifiez les logs Docker
2. Lisez la documentation technique (GESTION_ARTICLES_DOCUMENTATION.md)
3. Testez les cas de test ci-dessus

---

**Date** : 21/01/2026  
**Version** : 1.0  
**Status** : ✅ Prêt pour test
