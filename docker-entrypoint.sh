#!/bin/sh
php artisan config:clear || true
php artisan optimize:clear || true
php artisan storage:link || true
php artisan migrate --force || true
echo "starting supervisord..."
/usr/bin/supervisord -c /etc/supervisord.conf 2>&1
echo "supervisord exited with: $?"