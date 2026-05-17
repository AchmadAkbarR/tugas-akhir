#!/bin/sh

# Sesuaikan port
sed -i "s/listen 80;/listen ${PORT:-80};/g" /etc/nginx/nginx.conf

# Migrasi
php artisan migrate --force || true

# Start PHP-FPM di background dengan cara yang benar di Alpine
/usr/local/sbin/php-fpm --nodaemonize &

# Tunggu FPM siap
sleep 2

# Start Nginx
exec nginx -g "daemon off;"