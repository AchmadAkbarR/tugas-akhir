FROM php:8.2-fpm-alpine

WORKDIR /app
ENV CACHE_BUST=1
RUN apk add --no-cache \
    nginx \
    curl \
    git \
    libpq \
    libpq-dev \
    sqlite-dev \
    supervisor

RUN docker-php-ext-install pdo pdo_mysql pdo_sqlite pdo_pgsql bcmath

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

RUN rm -f /etc/nginx/nginx.conf
COPY nginx.conf /etc/nginx/nginx.conf
COPY supervisord.conf /etc/supervisord.conf
COPY docker-entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

RUN composer install --no-dev --optimize-autoloader --no-scripts && \
    chown -R www-data:www-data /app

RUN chmod -R 755 /app/storage /app/bootstrap/cache

RUN cp .env.example .env || true
RUN php artisan key:generate || true

EXPOSE 8080

ENTRYPOINT /usr/local/bin/entrypoint.sh