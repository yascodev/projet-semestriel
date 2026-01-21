-- ============================================================================
-- Migrations/Modifications de Schéma pour la Gestion des Articles
-- ============================================================================
-- NOTE: Le schéma de base devrait déjà avoir les tables nécessaires
-- Ce fichier docummente la structure requise et les améliorations optionnelles

-- ============================================================================
-- 1. TABLES PRINCIPALES (doivent déjà exister)
-- ============================================================================

-- Table articles
-- CREATE TABLE articles (
--     id SERIAL PRIMARY KEY,
--     title VARCHAR(255) NOT NULL,
--     slug VARCHAR(255) UNIQUE NOT NULL,
--     content TEXT,
--     excerpt TEXT,
--     status VARCHAR(50) DEFAULT 'draft', -- draft, published, archived
--     author_id INTEGER NOT NULL REFERENCES "user"(id),
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--     updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--     published_at TIMESTAMP NULL,
-- );

-- Table catégories
-- CREATE TABLE category (
--     id SERIAL PRIMARY KEY,
--     name VARCHAR(255) NOT NULL,
--     slug VARCHAR(255) UNIQUE NOT NULL,
--     description TEXT,
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--     updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
-- );

-- Table tags
-- CREATE TABLE tags (
--     id SERIAL PRIMARY KEY,
--     name VARCHAR(255) NOT NULL,
--     slug VARCHAR(255) UNIQUE NOT NULL,
--     description TEXT,
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--     updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
-- );

-- Table de jointure article_category (M:N)
-- CREATE TABLE article_category (
--     article_id INTEGER NOT NULL REFERENCES articles(id) ON DELETE CASCADE,
--     category_id INTEGER NOT NULL REFERENCES category(id) ON DELETE CASCADE,
--     PRIMARY KEY (article_id, category_id)
-- );

-- Table de jointure article_tag (M:N)
-- CREATE TABLE article_tag (
--     article_id INTEGER NOT NULL REFERENCES articles(id) ON DELETE CASCADE,
--     tag_id INTEGER NOT NULL REFERENCES tags(id) ON DELETE CASCADE,
--     PRIMARY KEY (article_id, tag_id)
-- );

-- Table utilisateurs
-- CREATE TABLE "user" (
--     id SERIAL PRIMARY KEY,
--     email VARCHAR(255) UNIQUE NOT NULL,
--     password VARCHAR(255) NOT NULL,
--     role VARCHAR(20) DEFAULT 'author', -- admin, editor, author
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--     updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
-- );

-- ============================================================================
-- 2. AMÉLIORATIONS OPTIONNELLES
-- ============================================================================

-- Ajouter des indexes pour améliorer les performances
CREATE INDEX IF NOT EXISTS idx_articles_author_id ON articles(author_id);
CREATE INDEX IF NOT EXISTS idx_articles_status ON articles(status);
CREATE INDEX IF NOT EXISTS idx_articles_created_at ON articles(created_at DESC);
CREATE INDEX IF NOT EXISTS idx_articles_published_at ON articles(published_at DESC);
CREATE INDEX IF NOT EXISTS idx_articles_slug ON articles(slug);

CREATE INDEX IF NOT EXISTS idx_user_email ON "user"(email);
CREATE INDEX IF NOT EXISTS idx_user_role ON "user"(role);

CREATE INDEX IF NOT EXISTS idx_category_slug ON category(slug);
CREATE INDEX IF NOT EXISTS idx_tags_slug ON tags(slug);

-- ============================================================================
-- 3. DONNÉES DE TEST (optionnel)
-- ============================================================================

-- Créer un utilisateur test admin
-- INSERT INTO "user" (email, password, role, created_at, updated_at) 
-- VALUES (
--     'admin@example.com',
--     '$2y$10$abcdefghijklmnopqrstuvwxyz', -- Utiliser password_hash('password', PASSWORD_BCRYPT)
--     'admin',
--     CURRENT_TIMESTAMP,
--     CURRENT_TIMESTAMP
-- ) ON CONFLICT DO NOTHING;

-- Créer un utilisateur test éditeur
-- INSERT INTO "user" (email, password, role, created_at, updated_at) 
-- VALUES (
--     'editor@example.com',
--     '$2y$10$abcdefghijklmnopqrstuvwxyz',
--     'editor',
--     CURRENT_TIMESTAMP,
--     CURRENT_TIMESTAMP
-- ) ON CONFLICT DO NOTHING;

-- Créer un utilisateur test auteur
-- INSERT INTO "user" (email, password, role, created_at, updated_at) 
-- VALUES (
--     'author@example.com',
--     '$2y$10$abcdefghijklmnopqrstuvwxyz',
--     'author',
--     CURRENT_TIMESTAMP,
--     CURRENT_TIMESTAMP
-- ) ON CONFLICT DO NOTHING;

-- Créer des catégories
-- INSERT INTO category (name, slug, description, created_at, updated_at) 
-- VALUES 
--     ('Technologie', 'technologie', 'Articles sur la technologie', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
--     ('Lifestyle', 'lifestyle', 'Articles sur le style de vie', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
--     ('Actualité', 'actualite', 'Les dernières actualités', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
--     ('Tutoriels', 'tutoriels', 'Guides et tutoriels', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
-- ON CONFLICT DO NOTHING;

-- Créer des tags
-- INSERT INTO tags (name, slug, description, created_at, updated_at) 
-- VALUES 
--     ('JavaScript', 'javascript', 'Langage de programmation JavaScript', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
--     ('PHP', 'php', 'Langage PHP', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
--     ('React', 'react', 'Framework JavaScript React', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
--     ('PostgreSQL', 'postgresql', 'Base de données PostgreSQL', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
--     ('Docker', 'docker', 'Conteneurisation avec Docker', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
--     ('Web', 'web', 'Développement web', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
--     ('Backend', 'backend', 'Développement backend', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
--     ('Frontend', 'frontend', 'Développement frontend', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
-- ON CONFLICT DO NOTHING;

-- ============================================================================
-- 4. VÉRIFICATION DE L'INTÉGRITÉ (au besoin)
-- ============================================================================

-- Vérifier que toutes les tables existent
-- SELECT table_name FROM information_schema.tables 
-- WHERE table_schema = 'public' 
-- ORDER BY table_name;

-- Vérifier les colonnes de la table articles
-- SELECT column_name, data_type, is_nullable, column_default 
-- FROM information_schema.columns 
-- WHERE table_name = 'articles' 
-- ORDER BY ordinal_position;

-- Compter les articles par statut
-- SELECT status, COUNT(*) as count FROM articles GROUP BY status;

-- Compter les articles par auteur
-- SELECT u.email, COUNT(a.id) as count 
-- FROM "user" u 
-- LEFT JOIN articles a ON a.author_id = u.id 
-- GROUP BY u.id, u.email;

-- ============================================================================
-- 5. NETTOYAGE (si nécessaire)
-- ============================================================================

-- Supprimer les articles orphelins
-- DELETE FROM articles WHERE author_id NOT IN (SELECT id FROM "user");

-- Réinitialiser les séquences
-- ALTER SEQUENCE articles_id_seq RESTART;
-- ALTER SEQUENCE category_id_seq RESTART;
-- ALTER SEQUENCE tags_id_seq RESTART;
-- ALTER SEQUENCE "user_id_seq" RESTART;

-- ============================================================================
-- 6. SAUVEGARDE ET RESTAURATION
-- ============================================================================

-- Backup complet (à exécuter depuis le terminal):
-- docker exec projet-semst-postgres pg_dump -U postgres > backup.sql

-- Restore depuis backup:
-- docker exec -i projet-semst-postgres psql -U postgres < backup.sql

-- Backup tableau spécifique:
-- docker exec projet-semst-postgres pg_dump -U postgres -t articles > articles_backup.sql

-- ============================================================================
-- Notes importantes:
-- ============================================================================
-- 1. Les commentaires (--) sont en PostgreSQL
-- 2. Adapter les noms de table si différents
-- 3. Le mot-clé "user" est protégé, utilisé avec "\"user\""
-- 4. Les statuts sont : draft, published, archived
-- 5. Les rôles sont : admin, editor, author
-- 6. Les passwords doivent être hashés avec PASSWORD_BCRYPT
-- 7. Les timestamps doivent être au format 'YYYY-MM-DD HH:MM:SS'
