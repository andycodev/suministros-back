# 1. Usar PHP 8.2 con Apache
FROM php:8.2-apache

# 2. Directorio de trabajo
WORKDIR /var/www/html

# 3. Instalar dependencias del sistema y extensiones de PHP
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libwebp-dev \
    libxpm-dev \
    libpq-dev \
    locales \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) gd pdo_mysql pdo_pgsql mbstring exif pcntl bcmath zip intl \
    && docker-php-ext-enable pdo_mysql pdo_pgsql mbstring exif pcntl bcmath zip intl

# 4. Instalar Node.js (v20)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# 5. Instalar Composer de forma global
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 6. Configurar Apache
RUN a2enmod rewrite
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf \
    && sed -i 's/DocumentRoot \/var\/www\/html/DocumentRoot \/var\/www\/html\/public/g' /etc/apache2/sites-available/000-default.conf

# 7. Copiar los archivos del proyecto
COPY . /var/www/html/

# 8. PREPARACIÓN CRÍTICA: Crear carpetas y dar permisos antes de Composer
RUN rm -rf vendor node_modules bootstrap/cache/* \
    && mkdir -p storage/framework/cache/data \
    && mkdir -p storage/framework/app/cache \
    && mkdir -p storage/framework/sessions \
    && mkdir -p storage/framework/views \
    && mkdir -p bootstrap/cache \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# 9. Instalar dependencias de PHP (ahora sí encontrará la carpeta writable)
# Usamos el usuario www-data para que los archivos generados le pertenezcan a Apache
USER www-data
RUN composer install --no-dev --optimize-autoloader --no-interaction

# 10. Volver a root para instalar Node y terminar
USER root
RUN npm install && npm run build

# 11. Permisos finales de seguridad
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# 12. Exponer puerto 80
EXPOSE 80

# 13. Comando de inicio
CMD ["apache2-foreground"]