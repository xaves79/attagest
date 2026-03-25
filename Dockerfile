FROM php:8.4-apache
RUN apt-get update && apt-get install -y libpq-dev libzip-dev libpng-dev unzip \
    && docker-php-ext-install pdo_pgsql zip gd \
    && a2enmod rewrite \
    && sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html
COPY . /var/www/html
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install --no-dev --no-scripts --optimize-autoloader --ignore-platform-reqs
RUN php artisan key:generate \
    && php artisan config:cache \
    && php artisan route:cache || true
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 755 storage bootstrap/cache

EXPOSE 8080
CMD apache2-foreground
