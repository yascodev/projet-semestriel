#!/bin/bash
set -e

# Initialiser la base de données si DATABASE_URL est fourni
if [ -n "$DATABASE_URL" ]; then
    echo "==> Initialisation de la base de données..."
    # Installer psql si pas dispo
    if ! command -v psql &> /dev/null; then
        apt-get install -y postgresql-client 2>/dev/null
    fi

    for sql_file in /var/www/html/database/init/*.sql; do
        echo "  → Exécution : $sql_file"
        psql "$DATABASE_URL" -f "$sql_file" 2>&1 || true
    done
    echo "==> Base de données prête."
fi

# Démarrer Apache
exec apache2-foreground
