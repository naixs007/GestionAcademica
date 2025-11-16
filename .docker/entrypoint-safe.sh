#!/bin/sh

# Script simplificado para despliegue en producción
# Evita errores comunes con cache de rutas y configuración

echo "========================================="
echo "Entrypoint - Modo Producción Simplificado"
echo "========================================="

# Esperar a que la base de datos esté lista (con timeout)
echo "⏳ Esperando conexión a base de datos..."
MAX_RETRIES=30
RETRY_COUNT=0

while ! php artisan db:show >/dev/null 2>&1; do
    RETRY_COUNT=$((RETRY_COUNT+1))
    if [ $RETRY_COUNT -ge $MAX_RETRIES ]; then
        echo "❌ ERROR: Timeout esperando base de datos ($MAX_RETRIES intentos)"
        exit 1
    fi
    echo "   Intento $RETRY_COUNT/$MAX_RETRIES - durmiendo 2s..."
    sleep 2
done
echo "✅ Base de datos conectada!"

# Limpiar TODA la caché existente
echo ""
echo "🧹 Limpiando caché..."
php artisan optimize:clear >/dev/null 2>&1 || true

# Ejecutar migraciones
echo ""
echo "📊 Ejecutando migraciones..."
if php artisan migrate --force 2>&1; then
    echo "✅ Migraciones completadas"
else
    echo "⚠️  Las migraciones fallaron o ya están aplicadas"
    php artisan migrate:status 2>&1 | head -n 20
fi

# Seeders (solo si se especifica)
if [ "${RUN_SEEDERS:-false}" = "true" ]; then
    echo ""
    echo "🌱 Ejecutando seeders..."
    php artisan db:seed --force 2>&1 || echo "⚠️  Seeders ya ejecutados o fallaron"
fi

# Storage link
echo ""
echo "🔗 Creando enlace de storage..."
php artisan storage:link 2>/dev/null || echo "   (ya existe)"

# Permisos
echo ""
echo "🔐 Configurando permisos..."
chmod -R 775 /var/www/html/storage 2>/dev/null || true
chmod -R 775 /var/www/html/bootstrap/cache 2>/dev/null || true
chown -R www-data:www-data /var/www/html/storage 2>/dev/null || true
chown -R www-data:www-data /var/www/html/bootstrap/cache 2>/dev/null || true
echo "✅ Permisos configurados"

# Cache SOLO de configuración (sin rutas ni vistas que pueden causar errores)
echo ""
echo "⚡ Optimizando aplicación..."
if php artisan config:cache 2>&1; then
    echo "✅ Configuración cacheada"
else
    echo "⚠️  No se pudo cachear configuración"
fi

# NO cachear rutas ni vistas en producción si causa problemas
# Esto es más lento pero más seguro
echo "ℹ️  Cache de rutas y vistas omitido (modo seguro)"

# Iniciar PHP-FPM
echo ""
echo "🚀 Iniciando PHP-FPM..."
/usr/local/sbin/php-fpm -D

echo ""
echo "========================================="
echo "✅ Aplicación lista en modo producción"
echo "========================================="
echo ""

# Mostrar información útil
echo "📋 Información del sistema:"
echo "   PHP Version: $(php -v | head -n 1)"
echo "   Laravel Version: $(php artisan --version)"
echo "   Environment: ${APP_ENV:-production}"
echo ""

# Ejecutar comando principal (NGINX)
exec "$@"
