#!/bin/sh
set -e

# Start PHP-FPM and Nginx together
php-fpm -F &
nginx -g "daemon off;"
