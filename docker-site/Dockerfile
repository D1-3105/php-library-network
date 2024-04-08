# Используем базовый образ Debian Buster для установки NGINX и PHP-FPM
FROM debian:buster

RUN apt-get update && apt-get install -y nginx php-fpm php-mysql && apt-get install -y inotify-tools

RUN rm /etc/nginx/sites-enabled/default

COPY nginx/nginx-site.conf /etc/nginx/sites-available/myapp.conf
RUN ln -s /etc/nginx/sites-available/myapp.conf /etc/nginx/sites-enabled/

COPY site_libraries/* /var/www/html/

COPY fpm-reloader.sh /usr/local/bin/fpm-reloader.sh
RUN chmod +x /usr/local/bin/fpm-reloader.sh

EXPOSE 80

COPY docker-site/php.ini /etc/php/7.3/fpm/php.ini

CMD service php7.3-fpm start && /usr/local/bin/fpm-reloader.sh & nginx -g 'daemon off;'
