FROM php:8.3-apache
RUN apt-get update \
    && apt-get install -y libpq-dev libzip-dev unzip \
    && docker-php-ext-install pdo pdo_pgsql zip \
    && a2enmod rewrite
WORKDIR /var/www/html
COPY . .
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install --no-dev --optimize-autoloader \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN php artisan config:cache && php artisan route:cache && php artisan view:cache || true
EXPOSE 8080
CMD apache2-foreground
