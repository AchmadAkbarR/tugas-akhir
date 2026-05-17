FROM php:8.2-fpm-alpine

WORKDIR /app

# Install system packages
RUN apk add --no-cache \
    nginx \
    curl \
    git \
    libpq \
    libpq-dev \
    sqlite-dev

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_sqlite pdo_pgsql bcmath

# Copy Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy project files
COPY . .

# Copy Nginx config as main config file
RUN rm -f /etc/nginx/nginx.conf
COPY nginx.conf /etc/nginx/nginx.conf

# Copy entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader --no-scripts && \
    chown -R www-data:www-data /app

# Set permissions
RUN chmod -R 755 /app/storage /app/bootstrap/cache

# Create .env if not exists
RUN cp .env.example .env || true

# Generate app key
RUN php artisan key:generate || true

RUN docker-php-ext-install pdo pdo_mysql pdo_sqlite pdo_pgsql bcmath

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]


