FROM php:8.2-cli

# Install required system packages and PHP extensions
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpq-dev libonig-dev libzip-dev \
    && docker-php-ext-install pdo pdo_pgsql mbstring zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy Laravel files
COPY . .

# Install Laravel dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Permissions
RUN chmod -R 777 storage bootstrap/cache

# Expose port
EXPOSE 8080

# Start Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
