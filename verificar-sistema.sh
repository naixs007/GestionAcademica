#!/bin/bash

echo "======================================"
echo "🔍 DIAGNÓSTICO DEL SISTEMA"
echo "======================================"
echo ""

# Colores
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Función para check
check_status() {
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}✓${NC} $1"
        return 0
    else
        echo -e "${RED}✗${NC} $1"
        return 1
    fi
}

echo "1️⃣  Verificando PHP..."
php -v > /dev/null 2>&1
check_status "PHP instalado"

echo ""
echo "2️⃣  Verificando Composer..."
composer --version > /dev/null 2>&1
check_status "Composer instalado"

echo ""
echo "3️⃣  Verificando Node/NPM..."
node -v > /dev/null 2>&1
check_status "Node.js instalado"
npm -v > /dev/null 2>&1
check_status "NPM instalado"

echo ""
echo "4️⃣  Verificando Archivos de Configuración..."
[ -f .env ] && check_status ".env existe" || echo -e "${RED}✗${NC} .env NO existe (copiar desde .env.example)"
[ -f composer.json ] && check_status "composer.json existe"
[ -f package.json ] && check_status "package.json existe"

echo ""
echo "5️⃣  Verificando Variables de Entorno Críticas..."
source .env 2>/dev/null
[ ! -z "$APP_KEY" ] && check_status "APP_KEY configurada" || echo -e "${RED}✗${NC} APP_KEY faltante (ejecutar: php artisan key:generate)"
[ ! -z "$DB_HOST" ] && check_status "DB_HOST configurada"
[ ! -z "$DB_DATABASE" ] && check_status "DB_DATABASE configurada"
[ ! -z "$DB_USERNAME" ] && check_status "DB_USERNAME configurada"

echo ""
echo "6️⃣  Verificando Conexión a Base de Datos..."
php artisan db:show > /dev/null 2>&1
if [ $? -eq 0 ]; then
    check_status "Conexión a base de datos exitosa"
    echo ""
    echo "   📊 Información de la base de datos:"
    php artisan db:show 2>/dev/null | head -n 10
else
    echo -e "${RED}✗${NC} No se puede conectar a la base de datos"
    echo -e "${YELLOW}   Revisar credenciales en .env${NC}"
fi

echo ""
echo "7️⃣  Verificando Estado de Migraciones..."
php artisan migrate:status 2>/dev/null | head -n 15
PENDING=$(php artisan migrate:status 2>/dev/null | grep -c "Pending")
if [ $PENDING -gt 0 ]; then
    echo -e "${YELLOW}⚠${NC}  Hay $PENDING migraciones pendientes"
    echo -e "${YELLOW}   Ejecutar: php artisan migrate${NC}"
else
    check_status "Todas las migraciones ejecutadas"
fi

echo ""
echo "8️⃣  Verificando Datos en Base de Datos..."
echo "   📈 Contadores:"
php artisan tinker --execute="echo 'Usuarios: ' . App\\Models\\User::count() . PHP_EOL;" 2>/dev/null
php artisan tinker --execute="echo 'Docentes: ' . App\\Models\\Docente::count() . PHP_EOL;" 2>/dev/null
php artisan tinker --execute="echo 'Materias: ' . App\\Models\\Materia::count() . PHP_EOL;" 2>/dev/null
php artisan tinker --execute="echo 'Grupos: ' . App\\Models\\Grupo::count() . PHP_EOL;" 2>/dev/null
php artisan tinker --execute="echo 'Roles: ' . Spatie\\Permission\\Models\\Role::count() . PHP_EOL;" 2>/dev/null

echo ""
echo "9️⃣  Verificando Assets Compilados..."
[ -d public/build ] && check_status "Carpeta public/build existe" || echo -e "${RED}✗${NC} public/build NO existe (ejecutar: npm run build)"
[ -f public/build/manifest.json ] && check_status "manifest.json existe" || echo -e "${RED}✗${NC} manifest.json faltante"
if [ -d public/build/assets ]; then
    CSS_COUNT=$(ls public/build/assets/*.css 2>/dev/null | wc -l)
    JS_COUNT=$(ls public/build/assets/*.js 2>/dev/null | wc -l)
    echo -e "${GREEN}✓${NC} Assets: $CSS_COUNT CSS, $JS_COUNT JS"
fi

echo ""
echo "🔟 Verificando Permisos de Carpetas..."
[ -w storage ] && check_status "storage/ tiene permisos de escritura" || echo -e "${RED}✗${NC} storage/ sin permisos (ejecutar: chmod -R 775 storage)"
[ -w bootstrap/cache ] && check_status "bootstrap/cache/ tiene permisos de escritura" || echo -e "${RED}✗${NC} bootstrap/cache/ sin permisos"

echo ""
echo "1️⃣1️⃣  Verificando Caché..."
[ -f bootstrap/cache/config.php ] && echo -e "${GREEN}✓${NC} Caché de configuración generada" || echo -e "${YELLOW}⚠${NC}  Sin caché de config (ejecutar: php artisan config:cache)"
[ -f bootstrap/cache/routes-v7.php ] && echo -e "${GREEN}✓${NC} Caché de rutas generada" || echo -e "${YELLOW}⚠${NC}  Sin caché de rutas (ejecutar: php artisan route:cache)"

echo ""
echo "1️⃣2️⃣  Verificando Logs Recientes..."
if [ -f storage/logs/laravel.log ]; then
    ERROR_COUNT=$(tail -n 100 storage/logs/laravel.log 2>/dev/null | grep -c "ERROR")
    if [ $ERROR_COUNT -gt 0 ]; then
        echo -e "${YELLOW}⚠${NC}  Se encontraron $ERROR_COUNT errores recientes en logs"
        echo "   Últimos errores:"
        tail -n 100 storage/logs/laravel.log 2>/dev/null | grep "ERROR" | tail -n 3
    else
        check_status "Sin errores recientes en logs"
    fi
else
    echo -e "${YELLOW}⚠${NC}  No hay logs aún"
fi

echo ""
echo "======================================"
echo "📋 RESUMEN DE RECOMENDACIONES"
echo "======================================"

ISSUES=0

# Verificar problemas comunes
if [ ! -f .env ]; then
    echo -e "${RED}⚠${NC}  Crear archivo .env: cp .env.example .env"
    ISSUES=$((ISSUES+1))
fi

if [ -z "$APP_KEY" ]; then
    echo -e "${RED}⚠${NC}  Generar APP_KEY: php artisan key:generate"
    ISSUES=$((ISSUES+1))
fi

if ! php artisan db:show > /dev/null 2>&1; then
    echo -e "${RED}⚠${NC}  Configurar credenciales de base de datos en .env"
    ISSUES=$((ISSUES+1))
fi

if [ ! -d public/build ]; then
    echo -e "${RED}⚠${NC}  Compilar assets: npm install && npm run build"
    ISSUES=$((ISSUES+1))
fi

if [ ! -w storage ] || [ ! -w bootstrap/cache ]; then
    echo -e "${RED}⚠${NC}  Corregir permisos: chmod -R 775 storage bootstrap/cache"
    ISSUES=$((ISSUES+1))
fi

USER_COUNT=$(php artisan tinker --execute="echo App\\Models\\User::count();" 2>/dev/null)
if [ "$USER_COUNT" == "0" ] || [ -z "$USER_COUNT" ]; then
    echo -e "${YELLOW}⚠${NC}  Ejecutar seeders: php artisan db:seed --force"
    ISSUES=$((ISSUES+1))
fi

echo ""
if [ $ISSUES -eq 0 ]; then
    echo -e "${GREEN}✅ ¡Sistema configurado correctamente!${NC}"
else
    echo -e "${YELLOW}⚠  Se encontraron $ISSUES problemas que requieren atención${NC}"
fi

echo ""
echo "======================================"
echo "🔗 COMANDOS ÚTILES"
echo "======================================"
echo "• Limpiar caché: php artisan optimize:clear"
echo "• Migrar DB: php artisan migrate --force"
echo "• Seeders: php artisan db:seed --force"
echo "• Compilar assets: npm run build"
echo "• Ver logs: tail -f storage/logs/laravel.log"
echo "• Optimizar: php artisan optimize"
echo ""
