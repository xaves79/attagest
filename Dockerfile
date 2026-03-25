FROM php:8.4-apache
RUN apt-get update && apt-get install -y \
    libpq-dev libzip-dev libpng-dev libjpeg-dev libfreetype6-dev unzip \
    && docker-php-ext-install pdo_pgsql zip gd \
    && a2enmod rewrite

WORKDIR /var/www/html/public
COPY public /var/www/html/public
COPY .env /var/www/html/.env
COPY composer.json composer.lock /var/www/html/
COPY app bootstrap config /var/www/html/

# Composer dans racine
WORKDIR /var/www/html
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --no-scripts --optimize-autoloader

# Fix Apache + permissions
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf \
    && echo "ServerName localhost" >> /etc/apache2/apache2.conf \
    && chown -R www-data:www-data /var/www/html /var/www/html/public \
    && chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 8080
CMD apache2-foreground
