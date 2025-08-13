# Stage 1 — Build assets and vendor
FROM php:8.2-cli AS build
WORKDIR /app

# Install PHP extensions required by your packages
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libicu-dev libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install gd pcntl opcache pdo pdo_mysql intl zip exif bcmath dom \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy PHP dependency files and install
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --optimize-autoloader

# Install Bun (for JS build)
RUN curl -fsSL https://bun.sh/install | bash
ENV PATH="/root/.bun/bin:${PATH}"

# Copy JS dependency files and install
COPY package.json bun.lockb ./
RUN bun install --frozen-lockfile

# Copy the rest of the code
COPY . .

# Build frontend
RUN bun run build


# Stage 2 — Final runtime image
FROM php:8.2-fpm
WORKDIR /var/www/html

# Install runtime PHP extensions
RUN apt-get update && apt-get install -y \
    libzip-dev libicu-dev libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install gd pcntl opcache pdo pdo_mysql intl zip exif bcmath dom \
    && rm -rf /var/lib/apt/lists/*

# Copy vendor and built assets from build stage
COPY --from=build /app/vendor ./vendor
COPY --from=build /app/public/build ./public/build
COPY --from=build /app ./

# Set PHP upload limit
RUN echo "upload_max_filesize=200M" > /usr/local/etc/php/conf.d/uploads.ini \
 && echo "post_max_size=200M" >> /usr/local/etc/php/conf.d/uploads.ini

EXPOSE 9000
CMD ["php-fpm"]
