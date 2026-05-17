#!/bin/sh
php artisan migrate --force || true
exec supervisord -c /etc/supervisord.conf