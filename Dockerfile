# Stage 1: PHP & Composer
FROM php:8.3-fpm AS php-builder

# Install system dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
    git unzip libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libxml2-dev zip curl \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Set PHP upload limits
RUN { \
    echo "upload_max_filesize = 200M"; \
    echo "post_max_size = 200M"; \
    echo "memory_limit = 512M"; \
} > /usr/local/etc/php/conf.d/uploads.ini

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy composer files and install deps
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-interaction --prefer-dist

# Copy full Laravel source code AFTER composer deps
COPY . .

# Run artisan optimizations
RUN php artisan config:clear \
 && php artisan cache:clear \
 && php artisan route:cache \
 && php artisan view:cache

# Stage 2: Node for frontend (pnpm)
FROM node:20 AS node-builder

WORKDIR /app

# Install pnpm
RUN npm install -g pnpm

# Copy JS deps
COPY package.json pnpm-lock.yaml ./
RUN pnpm install --frozen-lockfile

# Copy frontend files and build
COPY . .
RUN pnpm run build

# Stage 3: Production image
FROM php:8.3-fpm

# Install runtime dependencies
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libxml2-dev zip curl \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Copy PHP config
COPY --from=php-builder /usr/local/etc/php/conf.d/uploads.ini /usr/local/etc/php/conf.d/uploads.ini

# Copy Laravel & vendor
WORKDIR /app
COPY --from=php-builder /app /app

# Copy built frontend assets
COPY --from=node-builder /app/public /app/public

# Environment vars for Nixpacks-style config
ENV NIXPACKS_PHP_FALLBACK_PATH=/index.php
ENV NIXPACKS_PHP_ROOT_DIR=/app/public

CMD ["php-fpm"]
