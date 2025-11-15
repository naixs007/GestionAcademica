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

# Instalar dependencias del sistema necesarias y PostgreSQL
RUN apk add --no-cache \
    nginx \
    git \
    libpq \
    libzip \
    libonig \
    bash \
    # Limpieza de caché
    && rm -rf /var/cache/apk/*

# Instalar extensiones de PHP necesarias (pgsql es crucial)
RUN docker-php-ext-install pdo_pgsql mbstring zip bcmath

# Establece el directorio de trabajo
WORKDIR /var/www/html

# Copia los archivos de la aplicación desde el directorio local
COPY . /var/www/html/

# Copia las dependencias instaladas en la etapa builder
COPY --from=builder /app/vendor /var/www/html/vendor

# Copia los archivos de configuración auxiliares
# NOTA: Debes crear la carpeta .docker/ y los archivos entrypoint.sh y nginx.conf
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
