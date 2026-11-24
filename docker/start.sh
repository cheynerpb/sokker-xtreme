#!/usr/bin/env bash
set -e
cd /var/www/html

# Render asigna el puerto en $PORT
PORT="${PORT:-10000}"
sed -ri "s/Listen [0-9]+/Listen ${PORT}/" /etc/apache2/ports.conf
sed -ri "s/<VirtualHost \*:[0-9]+>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf

# Cachear config/rutas con las variables de entorno de Render
php artisan config:cache
php artisan route:cache || true
php artisan view:cache || true

# Migraciones solo si se pide explícitamente (RUN_MIGRATIONS=true)
if [ "${RUN_MIGRATIONS}" = "true" ]; then
  php artisan migrate --force
fi

exec apache2-foreground
