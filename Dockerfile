FROM richarvey/nginx-php-fpm:latest

COPY . /var/www/html

RUN chown -R nginx:nginx /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

RUN composer install --no-dev --optimize-autoloader

CMD ["/start.sh"]