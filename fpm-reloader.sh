#!/bin/bash

WATCHED_PATH="/var/www/html"
PHP_FPM_SERVICE_NAME="php7.3-fpm"

ANOTHER_WATCHED_PATH="/etc/nginx/sites-enabled/"

while true; do
    if inotifywait -e modify,move,create,delete -r "$WATCHED_PATH"; then
        echo "Обнаружены изменения в $WATCHED_PATH. Перезапуск PHP-FPM..."
        service $PHP_FPM_SERVICE_NAME restart
    fi

    if inotifywait -e modify,move,create,delete -r "$ANOTHER_WATCHED_PATH"; then
        echo "Обнаружены изменения в $ANOTHER_WATCHED_PATH. Перезагрузка Nginx..."
        nginx -s reload
    fi
done
