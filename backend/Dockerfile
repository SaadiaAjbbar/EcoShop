FROM php:8.4-apache

RUN apt-get update && apt-get install -y \
    libpq-dev \
    zip unzip git curl \
    && docker-php-ext-install pdo pdo_pgsql

RUN a2enmod rewrite
RUN useradd -u 1000 -m saadiaajbbar

WORKDIR /var/www/html

COPY apache.conf /etc/apache2/sites-available/000-default.conf
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer