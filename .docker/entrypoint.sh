#!/bin/sh

# NO salir inmediatamente en errores menores
# set -e

echo "========================================="
echo "Running entrypoint script..."
echo "========================================="

# Esperar a que la base de datos esté lista
echo "Waiting for database connection..."
MAX_RETRIES=30
RETRY_COUNT=0

until php artisan db:show 2>/dev/null; do
    RETRY_COUNT=$((RETRY_COUNT+1))
    if [ $RETRY_COUNT -ge $MAX_RETRIES ]; then
        echo "ERROR: Database connection timeout after $MAX_RETRIES retries"
        exit 1
    fi
    echo "Database is unavailable - sleeping (attempt $RETRY_COUNT/$MAX_RETRIES)"
    sleep 2
done
echo "Database is ready!"

# Limpiar caché antes de migrar
echo "Clearing application cache..."
php artisan cache:clear 2>/dev/null || echo "  Cache already clear"
php artisan config:clear 2>/dev/null || echo "  Config already clear"
php artisan route:clear 2>/dev/null || echo "  Routes already clear"
php artisan view:clear 2>/dev/null || echo "  Views already clear"

# Ejecutar Migraciones
echo "Running database migrations..."
if php artisan migrate --force 2>&1; then
    echo "  Migrations completed successfully"
else
    echo "  ERROR: Migration failed"
    echo "  Checking if tables already exist..."
    php artisan migrate:status || true
fi

# Ejecutar los seeders solo si es necesario
if [ "${RUN_SEEDERS:-false}" = "true" ]; then
    echo "Running database seeders..."
    if php artisan db:seed --force 2>&1; then
        echo "  Seeders completed successfully"
    else
        echo "  WARNING: Seeders failed (may already have data)"
    fi
else
    echo "Skipping seeders (set RUN_SEEDERS=true to run)"
fi

# Crear link simbólico para storage
echo "Creating storage link..."
if [ ! -L /var/www/html/public/storage ]; then
    php artisan storage:link 2>/dev/null || echo "  Storage link already exists"
else
    echo "  Storage link already exists"
fi

# Verificar permisos de storage y bootstrap/cache
echo "Setting permissions..."
chmod -R 775 /var/www/html/storage 2>/dev/null || true
chmod -R 775 /var/www/html/bootstrap/cache 2>/dev/null || true
chown -R www-data:www-data /var/www/html/storage 2>/dev/null || true
chown -R www-data:www-data /var/www/html/bootstrap/cache 2>/dev/null || true

# Optimizar la aplicación para producción
echo "Optimizing application..."
php artisan config:cache 2>&1 || echo "  Config cache failed"
php artisan route:cache 2>&1 || echo "  Route cache failed"
php artisan view:cache 2>&1 || echo "  View cache failed"

# Inicia PHP-FPM en segundo plano
echo "Starting PHP-FPM..."
/usr/local/sbin/php-fpm -D

echo "========================================="
echo "Application ready!"
echo "========================================="

# Ejecuta el comando pasado a CMD (en este caso, iniciar NGINX)
exec "$@"

