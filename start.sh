#!/usr/bin/env bash
echo "Installation des dépendances..."
composer install --no-dev --working-dir=/var/www/html

echo "Cache des configurations..."
php artisan config:cache

echo "Cache des routes..."
php artisan route:cache

echo "Lancement des migrations..."
php artisan migrate --force

echo "Démarrage du serveur..."
php-fpm