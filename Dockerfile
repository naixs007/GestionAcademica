# Imagen base con PHP CLI 8.2
FROM php:8.2-cli

# Instalar dependencias del sistema y extensiones de PostgreSQL
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Instalar Composer (desde la imagen oficial)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Carpeta de trabajo dentro del contenedor
WORKDIR /app

# Copiar el código del proyecto al contenedor
COPY . .

# Instalar dependencias de Laravel
RUN composer install --no-dev --optimize-autoloader

# Comando para arrancar Laravel usando el servidor embebido de PHP
CMD php artisan serve --host=0.0.0.0 --port=${PORT:-10000}
