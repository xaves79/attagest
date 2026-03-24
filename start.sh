#!/bin/bash
# Démarrer PHP-FPM
php-fpm -D

# Lancer les migrations (optionnel, peut aussi se faire manuellement)
php artisan migrate --force

# Démarrer Nginx
nginx -g "daemon off;"