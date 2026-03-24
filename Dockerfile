FROM richarvey/nginx-php-fpm:latest

COPY . /var/www/html

RUN chown -R nginx:nginx /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

RUN composer install --no-dev

COPY start.sh /start.sh
RUN chmod +x /start.sh

CMD ["/start.sh"]