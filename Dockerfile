FROM php:8.4-apache
RUN apt-get update && apt-get install -y \
    libpq-dev libzip-dev libpng-dev libjpeg-dev libfreetype6-dev unzip \
    && docker-php-ext-install pdo_pgsql zip gd \
    && a2enmod rewrite
WORKDIR /var/www/html
COPY . /var/www/html
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --no-scripts --optimize-autoloader
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache \
    && echo "ServerName localhost" >> /etc/apache2/apache2.conf
EXPOSE 8080
CMD apache2-foreground
