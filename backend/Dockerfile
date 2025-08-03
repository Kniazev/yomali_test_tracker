FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev zip libonig-dev libxml2-dev libicu-dev libpq-dev \
    && docker-php-ext-install pdo pdo_mysql zip intl opcache

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . /var/www

RUN composer install --no-interaction --optimize-autoloader

RUN chown -R www-data:www-data var vendor

EXPOSE 9000

CMD ["php-fpm"]