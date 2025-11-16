#!/bin/sh

# Salir inmediatamente si un comando falla
set -e

echo "========================================="
echo "Running entrypoint script..."
echo "========================================="

# Esperar a que la base de datos esté lista
echo "Waiting for database connection..."
until php artisan db:show 2>/dev/null; do
    echo "Database is unavailable - sleeping"
    sleep 2
done
echo "Database is ready!"

# Limpiar caché antes de migrar
echo "Clearing application cache..."
php artisan cache:clear || true
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Ejecutar Migraciones
echo "Running database migrations..."
php artisan migrate --force

# Ejecutar los seeders solo si es necesario
# IMPORTANTE: En producción, esto debe ejecutarse solo la primera vez
# Si ya tienes datos, comenta esta línea
if [ "${RUN_SEEDERS:-false}" = "true" ]; then
    echo "Running database seeders..."
    php artisan db:seed --force
else
    echo "Skipping seeders (set RUN_SEEDERS=true to run)"
fi

# Crear link simbólico para storage
echo "Creating storage link..."
php artisan storage:link || true

# Optimizar la aplicación para producción
echo "Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Verificar permisos de storage y bootstrap/cache
echo "Setting permissions..."
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache

# Inicia PHP-FPM en segundo plano
echo "Starting PHP-FPM..."
/usr/local/sbin/php-fpm -D

echo "========================================="
echo "Application ready!"
echo "========================================="

# Ejecuta el comando pasado a CMD (en este caso, iniciar NGINX)
exec "$@"

