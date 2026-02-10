FROM php:8.2-fpm

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip libpq-dev

# Instalar extensiones de PHP
RUN docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copiamos el proyecto
COPY . .

# Instalamos dependencias SIN ejecutar scripts automáticos de Laravel
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Borramos cualquier archivo de caché que se haya subido por error desde tu PC local
RUN rm -rf bootstrap/cache/*.php storage/framework/cache/data/* storage/framework/views/*.php

# Ajustamos permisos
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

EXPOSE 10000

RUN mkdir -p /var/www/storage/framework/sessions
RUN mkdir -p /var/www/storage/framework/views
RUN mkdir -p /var/www/storage/framework/cache
RUN chown -R www-data:www-data /var/www/storage
RUN chmod -R 775 /var/www/storage

# El comando CMD ahora limpiará el caché justo antes de arrancar el servidor
#CMD php artisan config:clear && php artisan route:clear && php artisan serve --host=0.0.0.0 --port=10000
# Cambia el CMD final por este:
# Usamos ['sh', '-c', ...] para que entienda los comandos múltiples
CMD ["sh", "-c", "php artisan config:clear && php artisan route:clear && php artisan serve --host=0.0.0.0 --port=10000"]