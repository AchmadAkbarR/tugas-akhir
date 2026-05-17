#!/bin/sh
php artisan migrate --force || true
php-fpm --nodaemonize &
sleep 2
exec nginx -g "daemon off;"