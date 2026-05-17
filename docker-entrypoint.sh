#!/bin/sh
set -e

# Sesuaikan port dengan Railway
sed -i "s/listen 80;/listen ${PORT:-80};/g" /etc/nginx/nginx.conf

# Package discover
php artisan package:discover --ansi || true

# Migrasi database
php artisan migrate --force || true

# PHP-FPM di background
php-fpm -D

# Tunggu FPM siap
sleep 2

# Cek apakah FPM jalan
echo "=== Checking PHP-FPM ==="
ps aux | grep php-fpm

# Nginx di foreground
exec nginx -g "daemon off;"