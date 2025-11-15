#!/bin/sh

# Salir inmediatamente si un comando falla
set -e

echo "Running entrypoint script..."

# Ejecutar Migraciones (ESTO ES LO QUE PREVIENE EL ERROR DE BASE DE DATOS)
echo "Running database migrations..."
php artisan migrate --force

# 2. Ejecutar los seeders (cargar datos iniciales)
echo "Running database seeders..."
php artisan db:seed --force

# Optimizar la aplicación para producción
echo "Caching configuration and routes..."
php artisan config:cache
php artisan route:cache

# Inicia PHP-FPM en segundo plano
echo "Starting PHP-FPM..."
/usr/local/sbin/php-fpm -D

# Ejecuta el comando pasado a CMD (en este caso, iniciar NGINX)
exec "$@"
