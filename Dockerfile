# ─────────────────────────────────────────────────────────────────────────────
# Stage 1: Build (Composer)
# ─────────────────────────────────────────────────────────────────────────────
FROM composer:2.8 AS vendor

WORKDIR /app

# Copy only composer files to leverage Docker layer caching
COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --no-interaction \
    --prefer-dist

COPY . .
RUN composer dump-autoload --optimize --no-dev --no-scripts

# ─────────────────────────────────────────────────────────────────────────────
# Stage 2: Production (PHP-FPM)
# ─────────────────────────────────────────────────────────────────────────────
FROM php:8.4-fpm-alpine AS production

# System and Build dependencies
RUN apk add --no-cache \
    $PHPIZE_DEPS \
    bzip2-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libwebp-dev \
    libzip-dev \
    zip \
    unzip \
    oniguruma-dev \
    icu-dev \
    shadow

# Composer
COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer

# PHP Extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        bcmath \
        zip \
        gd \
        intl \
        opcache

# Redis extension
RUN pecl install redis \
    && docker-php-ext-enable redis

WORKDIR /var/www/html

# Copy from build stage
COPY --from=vendor /app/vendor ./vendor
COPY . .

# Copy entrypoint and set permissions
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Set ownership
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# DO NOT drop to USER www-data here; 
# the entrypoint.sh needs root to fix volume permissions.
# PHP-FPM will drop privileges itself when it starts.

ENTRYPOINT ["entrypoint.sh"]
EXPOSE 9000
CMD ["php-fpm"]
