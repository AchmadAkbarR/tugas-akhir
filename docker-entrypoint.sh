#!/bin/sh
set -e

# Sesuaikan port dengan Railway
sed -i "s/listen 80;/listen ${PORT:-80};/g" /etc/nginx/nginx.conf

# Migrasi database (pindahkan dari Dockerfile ke sini)
php artisan migrate --force || true

# PHP-FPM di background
php-fpm -D

# Nginx di foreground (supaya container tetap hidup)
exec nginx -g "daemon off;"
php artisan package:discover --ansi || true