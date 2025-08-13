# ===============================
# Stage 1: PHP dependencies
# ===============================
FROM dunglas/frankenphp:1-php8.3 AS php-builder

WORKDIR /app

# Copy composer files first (better caching)
COPY composer.json composer.lock ./

# Install PHP deps (no dev)
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

# Copy Laravel app
COPY . .

# ===============================
# Stage 2: Frontend build with Bun
# ===============================
FROM oven/bun:1 AS bun-builder

WORKDIR /app

# Copy package files
COPY package.json pnpm-lock.yaml ./

# Install frontend deps
RUN bun install --frozen-lockfile --ignore-scripts

# Copy from php-builder so vendor exists for flux.css import
COPY --from=php-builder /app ./

# Build frontend
RUN bun run build

# ===============================
# Stage 3: FrankenPHP final image
# ===============================
FROM dunglas/frankenphp:1-php8.3

# Install PHP extensions
RUN apt-get update && apt-get install -y \
    curl unzip git libicu-dev libzip-dev libpng-dev libjpeg-dev libfreetype6-dev libssl-dev \
 && install-php-extensions \
    gd pcntl opcache pdo pdo_mysql intl zip exif ftp bcmath redis \
 && rm -rf /var/lib/apt/lists/*

# PHP config
RUN echo "opcache.enable=1" > /usr/local/etc/php/conf.d/custom.ini \
 && echo "opcache.jit=tracing" >> /usr/local/etc/php/conf.d/custom.ini \
 && echo "opcache.jit_buffer_size=256M" >> /usr/local/etc/php/conf.d/custom.ini \
 && echo "memory_limit=512M" >> /usr/local/etc/php/conf.d/custom.ini \
 && echo "upload_max_filesize=20M" >> /usr/local/etc/php/conf.d/custom.ini \
 && echo "post_max_size=20M" >> /usr/local/etc/php/conf.d/custom.ini

# Copy Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working dir
WORKDIR /app

# Copy PHP vendor from php-builder
COPY --from=php-builder /app/vendor ./vendor

# Copy Laravel app (excluding vendor to avoid overwrite)
COPY . .

# Copy built frontend assets
COPY --from=bun-builder /app/public/build ./public/build

# Ensure permissions
RUN mkdir -p storage bootstrap/cache \
 && chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

# Cache config/routes/views
RUN php artisan config:cache && php artisan route:cache && php artisan view:cache

# Expose HTTP/HTTPS
EXPOSE 80 443

# Run FrankenPHP Octane
ENTRYPOINT ["php", "artisan", "octane:frankenphp", "--workers=3", "--max-requests=500", "--host=0.0.0.0", "--port=80"]
