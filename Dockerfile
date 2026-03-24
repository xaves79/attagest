FROM php:8.4-fpm

# Installer les dépendances système
RUN apt-get update && apt-get install -y \
    nginx \
    curl \
    zip \
    unzip \
    git \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    && apt-get clean

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copier les fichiers de l'application
COPY . /var/www/html
WORKDIR /var/www/html

# Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader

# Configuration de Nginx
RUN echo "server { \
    listen 8080; \
    server_name _; \
    root /var/www/html/public; \
    index index.php; \
    location / { \
        try_files \$uri \$uri/ /index.php?\$args; \
    } \
    location ~ \.php$ { \
        include snippets/fastcgi-php.conf; \
        fastcgi_pass unix:/var/run/php/php8.4-fpm.sock; \
    } \
}" > /etc/nginx/sites-available/default

# Permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Script de démarrage
COPY start.sh /start.sh
RUN chmod +x /start.sh

CMD ["/start.sh"]