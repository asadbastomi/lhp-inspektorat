#########################################
# 1. Build Stage: Bun + PHP vendor
#########################################
FROM oven/bun:latest AS build
WORKDIR /app

# Install PHP CLI and curl for Composer
RUN apt update && apt install -y php-cli curl unzip git \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

# Copy composer files first to cache deps
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --optimize-autoloader

# Copy Node package files first to cache deps
COPY package.json pnpm-lock.yaml ./
RUN bun install --frozen-lockfile

# Copy the rest of the source code
COPY . .

# Build frontend assets (vendor/livewire/flux/dist/flux.css now exists)
RUN bun run build


#########################################
# 2. Runtime Stage: FrankenPHP
#########################################
FROM dunglas/frankenphp

ENV SERVER_NAME="http://"

# Install required system libs
RUN apt update && apt install -y \
    curl unzip git libicu-dev libzip-dev libpng-dev libjpeg-dev \
    libfreetype6-dev libssl-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN install-php-extensions \
    gd \
    pcntl \
    opcache \
    pdo \
    pdo_mysql \
    intl \
    zip \
    exif \
    ftp \
    bcmath \
    redis

# PHP settings
RUN echo "opcache.enable=1" > /usr/local/etc/php/conf.d/custom.ini \
    && echo "opcache.jit=tracing" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "opcache.jit_buffer_size=256M" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "memory_limit=512M" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "upload_max_filesize=200M" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "post_max_size=200M" >> /usr/local/etc/php/conf.d/custom.ini

# Copy Composer from official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Prepare Laravel storage
RUN mkdir -p /app/storage /app/bootstrap/cache \
    && chown -R www-data:www-data /app/storage /app/bootstrap/cache \
    && chmod -R 775 /app/storage /app/bootstrap/cache

# Copy app source code
COPY . .

# Copy vendor & public/build from build stage
COPY --from=build /app/vendor /app/vendor
COPY --from=build /app/public/build /app/public/build

# Install production PHP dependencies
RUN composer install --prefer-dist --optimize-autoloader --no-interaction

# Enable extensions
RUN docker-php-ext-enable redis

EXPOSE 80 443

ENTRYPOINT ["php", "artisan", "octane:frankenphp", "--workers=3", "--max-requests=500", "--log-level=debug", "--host=0.0.0.0", "--port=80"]
