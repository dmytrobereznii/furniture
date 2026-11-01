FROM php:8.5-fpm

RUN pecl install -o -f redis \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis

RUN apt-get update && apt-get install -y libpq-dev libzip-dev unzip zlib1g-dev zip libpng-dev libsodium-dev libjpeg62-turbo-dev libwebp-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-jpeg --with-webp --with-freetype \
    && docker-php-ext-install gd mysqli pdo pdo_mysql zip sodium exif \
    && docker-php-ext-enable exif

COPY ./php.ini /usr/local/etc/php/conf.d/app.ini

WORKDIR /app
