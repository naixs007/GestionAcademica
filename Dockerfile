# ----------------------------------------------------
# ETAPA 1: ASSETS BUILDER (Node.js/Vite)
# ----------------------------------------------------
FROM node:18-alpine AS node_builder

WORKDIR /app

# Copia los archivos necesarios para Node/Vite
COPY package.json package-lock.json ./
RUN npm install

# Copia los archivos del proyecto y compila Vite
COPY . .
RUN npm run build # <-- ESTO CREA public/build/manifest.json

# ----------------------------------------------------
# ETAPA 2: PHP DEPENDENCY BUILDER (Composer)
# ----------------------------------------------------
FROM composer:2 AS php_builder

WORKDIR /app

# Copia el código completo (incluye 'artisan' para scripts)
COPY . .

# Instala dependencias de PHP
RUN composer install --no-dev --optimize-autoloader --no-interaction --ignore-platform-reqs

# ----------------------------------------------------
# ETAPA 3: PRODUCCIÓN (PHP-FPM/NGINX)
# ----------------------------------------------------
FROM php:8.2-fpm-alpine AS production

# 1. Instalar dependencias del sistema y extensiones de PHP
RUN apk update \
    && apk add --no-cache \
        nginx \
        git \
        bash \
        # Paquetes de desarrollo para compilación de extensiones:
        libpq-dev \
        libzip-dev \
        oniguruma-dev \
    # Instalar y habilitar las extensiones de PHP
    && docker-php-ext-install pdo_pgsql mbstring zip bcmath \
    # Limpieza de caché
    && rm -rf /var/cache/apk/*

# Establece el directorio de trabajo
WORKDIR /var/www/html

# Copia los archivos base de la aplicación
COPY . /var/www/html/

# Copia los activos compilados desde la etapa 'node_builder' (¡CRUCIAL para Vite!)
COPY --from=node_builder /app/public/build /var/www/html/public/build

# Copia la carpeta 'vendor' desde la etapa 'php_builder'
COPY --from=php_builder /app/vendor /var/www/html/vendor

# Copia los archivos de configuración auxiliares
COPY .docker/entrypoint.sh /usr/local/bin/entrypoint.sh
COPY .docker/nginx.conf /etc/nginx/nginx.conf

# Configuración de permisos
RUN chown -R www-data:www-data /var/www/html \
    && chmod +x /usr/local/bin/entrypoint.sh

# El puerto 8000 es el que usará NGINX para escuchar
EXPOSE 8000

# Script de entrada que ejecuta las migraciones antes de iniciar NGINX
ENTRYPOINT ["entrypoint.sh"]

# Iniciar NGINX
CMD ["nginx", "-g", "daemon off;"]
