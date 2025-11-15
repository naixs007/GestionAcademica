# ----------------------------------------------------
# ETAPA 1: BUILDER - Para instalar dependencias de Composer
# ----------------------------------------------------
FROM composer:2 AS builder

WORKDIR /app

# Copia solo los archivos necesarios para la instalación de Composer
COPY composer.json composer.lock ./

# Instala las dependencias de Laravel (solo producción)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# ----------------------------------------------------
# ETAPA 2: PRODUCCIÓN - Configuración de PHP-FPM y NGINX
# ----------------------------------------------------
# Usamos una imagen FPM ligera (Alpine)
FROM php:8.2-fpm-alpine AS production

# 1. Instalar dependencias del sistema y extensiones de PHP en un solo RUN
RUN apk update \
    && apk add --no-cache \
        nginx \
        git \
        libpq \
        libzip \
        bash \
        libpq-dev \
        libzip-dev \
        oniguruma-dev \
    # Instalar y habilitar las extensiones de PHP necesarias (pgsql es crucial)
    && docker-php-ext-install pdo_pgsql mbstring zip bcmath \
    # Limpieza de caché
    && rm -rf /var/cache/apk/*

# Establece el directorio de trabajo
WORKDIR /var/www/html

# Copia los archivos de la aplicación desde el directorio local
COPY . /var/www/html/

# Copia las dependencias instaladas en la etapa builder
COPY --from=builder /app/vendor /var/www/html/vendor

# Copia los archivos de configuración auxiliares (Entrypoint y NGINX)
# Verifica que la carpeta .docker/ y estos archivos existan en tu repositorio
COPY .docker/entrypoint.sh /usr/local/bin/entrypoint.sh
COPY .docker/nginx.conf /etc/nginx/nginx.conf

# Configuración de permisos
RUN chown -R www-data:www-data /var/www/html \
    && chmod +x /usr/local/bin/entrypoint.sh

# El puerto 8000 es el que usará NGINX para escuchar
EXPOSE 8000

# Usamos el entrypoint.sh para ejecutar las migraciones ANTES de iniciar los servicios
ENTRYPOINT ["entrypoint.sh"]

# El comando final que ejecuta el entrypoint.sh (que inicia NGINX y PHP-FPM)
CMD ["nginx", "-g", "daemon off;"]
