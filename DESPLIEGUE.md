# 📋 GUÍA DE DESPLIEGUE EN LA NUBE

## 🔍 Problema Común: "La información no se ve reflejada"

Este problema suele ocurrir por varias razones:

### 1️⃣ **Base de datos vacía o sin migrar**
- Las migraciones no se ejecutaron
- Los seeders no se corrieron
- Conexión incorrecta a la base de datos

### 2️⃣ **Archivos estáticos (Vite) no compilados**
- El `npm run build` no se ejecutó
- Los assets no están en `public/build/`
- Manifest de Vite faltante

### 3️⃣ **Permisos de carpetas**
- Sin permisos de escritura en `storage/`
- Sin permisos en `bootstrap/cache/`

### 4️⃣ **Variables de entorno incorrectas**
- APP_KEY no generada
- Conexión de base de datos mal configurada
- APP_ENV en modo incorrecto

---

## 🚀 PASOS PARA DESPLEGAR CORRECTAMENTE

### Opción A: Usando Docker (Recomendado)

```bash
# 1. Construir la imagen
docker build -t gestion-academica:latest .

# 2. Ejecutar con variables de entorno
docker run -d \
  -p 8000:8000 \
  -e APP_KEY="base64:tu-key-aqui" \
  -e DB_HOST="tu-db-host" \
  -e DB_PORT="5432" \
  -e DB_DATABASE="tu-db-name" \
  -e DB_USERNAME="tu-db-user" \
  -e DB_PASSWORD="tu-db-password" \
  -e RUN_SEEDERS="true" \
  --name gestion-academica \
  gestion-academica:latest

# 3. Verificar logs
docker logs -f gestion-academica
```

### Opción B: Despliegue Manual en Servidor

```bash
# 1. Clonar repositorio
git clone https://github.com/tu-usuario/GestionAcademica.git
cd GestionAcademica

# 2. Copiar archivo de entorno
cp .env.production.example .env
nano .env  # Editar con tus credenciales

# 3. Instalar dependencias PHP
composer install --no-dev --optimize-autoloader

# 4. Generar clave de aplicación
php artisan key:generate

# 5. Compilar assets frontend
npm install
npm run build

# 6. Configurar permisos
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# 7. Ejecutar migraciones
php artisan migrate --force

# 8. Ejecutar seeders (SOLO PRIMERA VEZ)
php artisan db:seed --force

# 9. Crear link de storage
php artisan storage:link

# 10. Optimizar para producción
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 11. Iniciar servidor (o configurar Nginx/Apache)
php artisan serve --host=0.0.0.0 --port=8000
```

---

## 🛠️ SOLUCIONES POR PLATAFORMA

### Railway

1. **Agregar variables de entorno en Dashboard:**
   - `APP_KEY` - Generar con `php artisan key:generate --show`
   - `APP_ENV=production`
   - `APP_DEBUG=false`
   - `DB_HOST=${{PGHOST}}`
   - `DB_DATABASE=${{PGDATABASE}}`
   - `DB_USERNAME=${{PGUSER}}`
   - `DB_PASSWORD=${{PGPASSWORD}}`
   - `RUN_SEEDERS=true` (primera vez)

2. **Configurar build:**
   ```
   Build Command: npm run build && composer install --no-dev
   Start Command: sh .docker/entrypoint.sh && php artisan serve --host=0.0.0.0 --port=$PORT
   ```

### Render

1. **Usar Blueprint (render.yaml)**
2. **Variables de entorno:**
   - Automáticamente detecta `DATABASE_URL`
   - Agregar manualmente: `APP_KEY`, `APP_ENV=production`

3. **Build Command:**
   ```bash
   composer install --no-dev && npm install && npm run build && php artisan migrate --force
   ```

### Heroku

```bash
# Agregar buildpacks
heroku buildpacks:add heroku/nodejs
heroku buildpacks:add heroku/php

# Configurar variables
heroku config:set APP_KEY=$(php artisan key:generate --show)
heroku config:set APP_ENV=production
heroku config:set APP_DEBUG=false

# Desplegar
git push heroku main

# Ejecutar migraciones
heroku run php artisan migrate --force

# Ejecutar seeders (primera vez)
heroku run php artisan db:seed --force
```

---

## ✅ VERIFICACIÓN POST-DESPLIEGUE

### 1. Verificar Base de Datos

```bash
# Conectarse al contenedor/servidor
docker exec -it gestion-academica sh
# o ssh a tu servidor

# Verificar conexión
php artisan db:show

# Ver migraciones
php artisan migrate:status

# Contar registros
php artisan tinker
>>> App\Models\User::count();
>>> App\Models\Docente::count();
>>> App\Models\Materia::count();
```

### 2. Verificar Assets

```bash
# Verificar que existan los archivos compilados
ls -la public/build/

# Debe mostrar:
# - manifest.json
# - assets/*.css
# - assets/*.js
```

### 3. Verificar Logs

```bash
# Ver logs de Laravel
tail -f storage/logs/laravel.log

# Ver logs de Docker
docker logs -f gestion-academica

# Ver logs de Nginx
tail -f /var/log/nginx/error.log
```

### 4. Probar Endpoints

```bash
# Health check
curl https://tu-dominio.com/up

# Login
curl https://tu-dominio.com/login

# Dashboard (requiere autenticación)
curl -L https://tu-dominio.com
```

---

## 🐛 TROUBLESHOOTING

### Error: "SQLSTATE[08006] Connection refused"
- **Causa:** Base de datos no accesible
- **Solución:** Verificar `DB_HOST`, `DB_PORT`, firewall, whitelist IP

### Error: "Class not found"
- **Causa:** Autoload no actualizado
- **Solución:** `composer dump-autoload`

### Error: "No application encryption key"
- **Causa:** APP_KEY no configurada
- **Solución:** `php artisan key:generate`

### Error: "Mix manifest not found"
- **Causa:** Assets no compilados
- **Solución:** `npm run build`

### Error: "Permission denied" en storage
- **Causa:** Permisos incorrectos
- **Solución:**
  ```bash
  chmod -R 775 storage bootstrap/cache
  chown -R www-data:www-data storage bootstrap/cache
  ```

### Las vistas están vacías (sin datos)
- **Causa:** Seeders no ejecutados
- **Solución:**
  ```bash
  php artisan db:seed --force
  # O específicamente:
  php artisan db:seed --class=RoleSeeder --force
  php artisan db:seed --class=ConfiguracionSeeder --force
  ```

### Errores 500 sin mensaje
- **Causa:** APP_DEBUG=false oculta errores
- **Solución temporal:**
  ```bash
  # Temporalmente activar debug
  php artisan config:clear
  export APP_DEBUG=true
  # Ver logs
  tail -f storage/logs/laravel.log
  ```

---

## 📝 CHECKLIST FINAL

Antes de declarar el despliegue exitoso, verificar:

- [ ] ✅ La aplicación responde en la URL
- [ ] ✅ Login funciona correctamente
- [ ] ✅ Dashboard muestra información
- [ ] ✅ Estilos CSS se cargan correctamente
- [ ] ✅ JavaScript funciona (menús, modales)
- [ ] ✅ Base de datos tiene datos (seeders ejecutados)
- [ ] ✅ Se pueden crear/editar registros
- [ ] ✅ PDFs se generan correctamente
- [ ] ✅ No hay errores en consola del navegador
- [ ] ✅ No hay errores en logs del servidor
- [ ] ✅ APP_DEBUG=false en producción
- [ ] ✅ HTTPS configurado (certificado SSL)

---

## 🆘 COMANDO DE EMERGENCIA

Si todo falla, ejecutar este script de reinicio completo:

```bash
#!/bin/bash
echo "🔄 Reiniciando aplicación..."

# Limpiar todo
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Recompilar
composer dump-autoload
npm run build

# Re-migrar (¡CUIDADO! Borra datos)
php artisan migrate:fresh --force --seed

# Optimizar
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Reinicio completo"
```

---

## 📞 CONTACTO DE SOPORTE

Si después de seguir todos los pasos aún tienes problemas:

1. Exporta los logs: `docker logs gestion-academica > logs.txt`
2. Captura screenshots de errores
3. Documenta los pasos que causaron el error
4. Revisa las variables de entorno con `php artisan config:show`
