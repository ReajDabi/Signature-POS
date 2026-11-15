FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
    bash curl libpng-dev libzip-dev zip unzip git \
    oniguruma-dev libxml2-dev freetype-dev libjpeg-turbo-dev

RUN docker-php-ext-install \
    pdo_mysql mbstring zip exif pcntl bcmath gd xml

COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction || true

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
