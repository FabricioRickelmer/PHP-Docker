FROM php:8.3-cli-alpine

# Install system dependencies & PHP extensions
RUN apk add --no-cache \
    bash \
    curl \
    git \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    mysql-client \
    netcat-openbsd \
    $PHPIZE_DEPS

RUN docker-php-ext-install pdo_mysql bcmath zip pcntl opcache

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Set permissions
RUN chmod +x ./wait-for-it.sh \
    && mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache

# Install PHP dependencies
RUN composer install --no-security-blocking --no-interaction --prefer-dist --optimize-autoloader

EXPOSE 8080

CMD ["sh", "-c", "./wait-for-it.sh mysql:3306 -t 60 -- php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=8080"]
