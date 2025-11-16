# 🔧 Solución al Error: "In Number.php line 439"

## 🔍 Diagnóstico del Problema

Este error ocurre típicamente durante el proceso de `php artisan route:cache` cuando Laravel intenta cachear las rutas pero encuentra problemas con:
- Parámetros de ruta con valores por defecto numéricos
- Problemas de formato en configuración
- Caché corrupto de configuración previa

## ✅ Soluciones

### Opción 1: Usar Entrypoint Seguro (Recomendado)

```bash
# Reconstruir la imagen con el entrypoint seguro
docker build -t gestion-academica:latest .

# Ejecutar con entrypoint alternativo
docker run -d \
  -p 8000:8000 \
  -e APP_KEY="tu-app-key" \
  -e DB_HOST="dpg-d4bec9ndiees73ah6m8g-a" \
  -e DB_PORT="5432" \
  -e DB_DATABASE="gestionacademica_db" \
  -e DB_USERNAME="gestionacademica_db_user" \
  -e DB_PASSWORD="tu-password" \
  -e RUN_SEEDERS="false" \
  --entrypoint="entrypoint-safe.sh" \
  --name gestion-academica \
  gestion-academica:latest \
  nginx -g "daemon off;"
```

### Opción 2: Variables de Entorno para Omitir Cache

```bash
# Agregar variable para deshabilitar route cache
docker run -d \
  -p 8000:8000 \
  -e APP_KEY="tu-app-key" \
  -e DB_HOST="dpg-d4bec9ndiees73ah6m8g-a" \
  -e DB_PORT="5432" \
  -e DB_DATABASE="gestionacademica_db" \
  -e DB_USERNAME="gestionacademica_db_user" \
  -e DB_PASSWORD="tu-password" \
  -e SKIP_ROUTE_CACHE="true" \
  --name gestion-academica \
  gestion-academica:latest
```

### Opción 3: Modificar Entrypoint en Plataforma Cloud

#### Para Railway:
1. Ve a Settings → Variables
2. Agrega:
   ```
   RAILWAY_START_COMMAND=/usr/local/bin/entrypoint-safe.sh nginx -g "daemon off;"
   ```

#### Para Render:
1. En render.yaml o Dashboard → Settings
2. Cambiar Start Command a:
   ```bash
   /usr/local/bin/entrypoint-safe.sh nginx -g "daemon off;"
   ```

## 🛠️ Solución Manual (Si ya está desplegado)

Si ya tienes el contenedor corriendo y presenta el error:

```bash
# 1. Conectarse al contenedor
docker exec -it gestion-academica sh

# 2. Limpiar TODO el caché
php artisan optimize:clear

# 3. Limpiar específicamente cache de rutas
php artisan route:clear

# 4. NO cachear rutas (dejar sin cache)
# O intentar cachear de nuevo:
php artisan route:cache 2>&1

# Si el comando anterior falla, verificar rutas problemáticas:
php artisan route:list | grep -i "number\|numeric\|default"

# 5. Reiniciar servicios
supervisorctl restart all
# O
nginx -s reload && pkill -USR2 php-fpm
```

## 🔍 Identificar la Ruta Problemática

```bash
# Ver todas las rutas con parámetros
php artisan route:list --columns=uri,name,action | grep "{"

# Buscar rutas con valores por defecto
grep -r "Route::" routes/ | grep -E "default|where.*numeric"
```

## 📝 Archivos a Revisar

### 1. routes/web.php
Buscar líneas como:
```php
// ❌ INCORRECTO - puede causar el error
Route::get('/item/{id?}', function ($id = 0) {
    // ...
})->where('id', '[0-9]+');

// ✅ CORRECTO
Route::get('/item/{id}', function ($id) {
    // ...
})->where('id', '[0-9]+');
```

### 2. routes/api.php
Similar a web.php

## 🚀 Prevención Futura

Para evitar este error en futuras versiones:

### 1. Actualizar Dockerfile
Ya incluye `entrypoint-safe.sh` que omite el cache de rutas

### 2. Agregar Variable de Control
```dockerfile
ENV SKIP_ROUTE_CACHE=true
```

### 3. Modificar entrypoint.sh
```bash
# En lugar de:
php artisan route:cache

# Usar:
if [ "${SKIP_ROUTE_CACHE:-false}" != "true" ]; then
    php artisan route:cache 2>&1 || echo "⚠️  Route cache skipped due to error"
fi
```

## ✅ Verificación Post-Solución

```bash
# 1. Verificar que la aplicación inicia
docker logs gestion-academica | tail -n 50

# 2. Verificar que responde
curl http://localhost:8000/up

# 3. Verificar rutas disponibles
docker exec gestion-academica php artisan route:list

# 4. Verificar sin errores
docker exec gestion-academica tail -f storage/logs/laravel.log
```

## 📊 Comparación de Métodos

| Método | Velocidad | Estabilidad | Recomendado |
|--------|-----------|-------------|-------------|
| Con route:cache | ⚡⚡⚡ Muy rápido | ⚠️ Puede fallar | Desarrollo |
| Sin route:cache | ⚡⚡ Normal | ✅ Estable | **Producción** |
| entrypoint-safe.sh | ⚡⚡ Normal | ✅✅ Muy estable | **✅ Recomendado** |

## 🆘 Si Nada Funciona

```bash
# Último recurso: Reconstruir desde cero
docker stop gestion-academica
docker rm gestion-academica
docker rmi gestion-academica:latest

# Construir con build args
docker build \
  --build-arg SKIP_OPTIMIZATION=true \
  -t gestion-academica:latest .

# Ejecutar sin optimizaciones
docker run -d \
  -p 8000:8000 \
  -e APP_ENV=production \
  -e APP_DEBUG=false \
  -e APP_KEY="tu-app-key" \
  -e DB_HOST="dpg-d4bec9ndiees73ah6m8g-a" \
  -e DB_DATABASE="gestionacademica_db" \
  -e DB_USERNAME="gestionacademica_db_user" \
  -e DB_PASSWORD="tu-password" \
  --entrypoint="entrypoint-safe.sh" \
  gestion-academica:latest \
  nginx -g "daemon off;"
```

## 📞 Información de Tu Despliegue

Según el log que proporcionaste:
- ✅ Base de datos: Conectada correctamente
- ✅ PostgreSQL: Versión 18.0
- ✅ Host: dpg-d4bec9ndiees73ah6m8g-a
- ✅ Database: gestionacademica_db
- ✅ User: gestionacademica_db_user
- ✅ Conexiones: 10 disponibles
- ✅ Tablas: 26 creadas (migraciones OK)
- ❌ Error: En cache de rutas (Number.php:439)

**Solución recomendada:** Usar `entrypoint-safe.sh` que omite el cache problemático.
