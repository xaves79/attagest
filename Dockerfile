FROM php:8.4-apache

RUN apt-get update && apt-get install -y \
    libpq-dev libzip-dev libpng-dev unzip \
    && docker-php-ext-install pdo_pgsql zip gd \
    && a2enmod rewrite

# Apache root = public
COPY public /var/www/html/
COPY composer.json composer.lock /tmp/
WORKDIR /tmp
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install --no-dev --optimize-autoloader --ignore-platform-reqs

COPY . /var/www/html/app/
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache \
    && echo "ServerName localhost" >> /etc/apache2/apache2.conf

EXPOSE 8080
CMD apache2-foreground
