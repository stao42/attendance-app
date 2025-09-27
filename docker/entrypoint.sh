#!/bin/bash
set -euo pipefail

cd /var/www/html

# Ensure application dependencies and runtime assets are ready before serving
if [ ! -f .env ]; then
  cp .env.example .env
fi

if [ ! -d vendor ]; then
  composer install --no-interaction --prefer-dist --no-progress
fi

# Ensure the SQLite database file exists when using sqlite
if grep -q '^DB_CONNECTION=sqlite' .env 2>/dev/null; then
  mkdir -p database
  if [ ! -f database/database.sqlite ]; then
    touch database/database.sqlite
  fi
fi

php artisan key:generate --force

if [ "${RUN_MIGRATIONS:-1}" != "0" ]; then
  if ! php artisan migrate --force --no-interaction; then
    echo "[warn] Migrations failed; continuing without forcing exit" >&2
  fi
fi

exec php artisan serve --host=0.0.0.0 --port=8000
