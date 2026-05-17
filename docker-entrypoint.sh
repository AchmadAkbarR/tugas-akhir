#!/bin/sh
php artisan migrate --force || true
exec /usr/bin/supervisord -c /etc/supervisord.conf