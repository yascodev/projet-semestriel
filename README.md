# CMS Blog multi-auteurs

Projet semestriel – Bloc 2 RNCP39235

## Description
Développement d’un CMS éditorial multi-auteurs en PHP orienté objet, basé sur un framework maison.

## Outils
- PHP
- Docker
- PostgreSQL
- Git / GitHub

## Organisation
Le projet est piloté selon une méthodologie SCRUM avec des sprints de deux semaines.

## Docker

Pour lancer l’environnement de développement :

docker-compose up -d

## Règles de contribution
- Commits clairs et descriptifs
- Respect de la convention de commit
- Une fonctionnalité par commit
- Respect de la convention de nommage des branches

## Convention de nommage des branches

Format : `type/description-courte`

Types de branches :

- **feature/** : nouvelle fonctionnalité (ex: `feature/user-authentication`)
- **fix/** : correction de bug (ex: `fix/login-error`)
- **hotfix/** : correction urgente en production (ex: `hotfix/security-patch`)
- **chore/** : tâches techniques/configuration (ex: `chore/setup-docker`)
- **docs/** : documentation uniquement (ex: `docs/api-documentation`)

Exemples :

- `feature/add-blog-posts`
- `fix/database-connection`
- `chore/update-dependencies`

## Convention de commit

Format : `type: description courte`

Types autorisés :

- feat : nouvelle fonctionnalité
- fix : correction de bug
- chore : configuration / maintenance
- docs : documentation

## Règle de validation des issues

Toute issue doit être testée et validée par au moins un autre membre de l’équipe avant d’être déplacée dans la colonne “Terminé”.
