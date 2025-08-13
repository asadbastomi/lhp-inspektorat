# ======================================
# 1️⃣ FRONTEND + COMPOSER BUILD STAGE
# ======================================
FROM oven/bun:latest AS build
WORKDIR /app

# Install PHP CLI + required PHP extensions for Composer
RUN apt-get update && apt-get install -y \
    php-cli \
    php-xml \
    php-mbstring \
    php-zip \
    php-curl \
    unzip \
    curl \
    git \
 && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer \
    | php -- --install-dir=/usr/local/bin --filename=composer

# Copy PHP dependencies first (composer.*) and install
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --optimize-autoloader

# Copy JS dependencies first (package.*) and install
COPY package.json pnpm-lock.yaml* bun.lockb* ./
RUN bun install --frozen-lockfile

# Copy the rest of the app and build frontend
COPY . .
RUN bun run build

# ======================================
# 2️⃣ PRODUCTION PHP-FPM STAGE
# ======================================
FROM php:8.2-fpm AS app
WORKDIR /var/www

# Install system deps and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libicu-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
 && docker-php-ext-install \
    gd \
    pcntl \
    opcache \
    pdo \
    pdo_mysql \
    intl \
    zip \
    exif \
    bcmath \
    dom \
 && rm -rf /var/lib/apt/lists/*

# Copy built app from build stage
COPY --from=build /app /var/www

# Set correct permissions
RUN chown -R www-data:www-data /var/www

# Increase PHP upload limits
RUN { \
    echo "upload_max_filesize=200M"; \
    echo "post_max_size=200M"; \
} > /usr/local/etc/php/conf.d/uploads.ini

CMD ["php-fpm"]
