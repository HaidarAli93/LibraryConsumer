# syntax=docker/dockerfile:1

# ---------------------------------------------------------------------------
# Stage 1: install Composer dependencies in isolation (better layer caching —
# this only re-runs when composer.json/composer.lock change).
# ---------------------------------------------------------------------------
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-scripts \
    --no-autoloader \
    --prefer-dist

COPY . .
RUN composer dump-autoload --optimize --no-dev

# ---------------------------------------------------------------------------
# Stage 2: the PHP-FPM application image.
# ---------------------------------------------------------------------------
FROM php:8.4-fpm AS app

RUN apt-get update && apt-get install -y --no-install-recommends \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libonig-dev \
        libxml2-dev \
        libzip-dev \
        libicu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        intl \
        opcache \
    && apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false \
    && rm -rf /var/lib/apt/lists/*

# Reasonable production OPcache defaults.
RUN { \
        echo 'opcache.enable=1'; \
        echo 'opcache.validate_timestamps=0'; \
        echo 'opcache.max_accelerated_files=20000'; \
        echo 'opcache.memory_consumption=192'; \
    } > /usr/local/etc/php/conf.d/opcache-recommended.ini

# Composer binary (used by docker-compose.override.yaml for local dev only —
# not needed at runtime once vendor/ is baked into the image).
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Run as a non-root user instead of root.
RUN useradd -m -u 1000 -s /bin/bash appuser

WORKDIR /var/www

COPY --chown=appuser:appuser . .
COPY --from=vendor --chown=appuser:appuser /app/vendor ./vendor

RUN chown -R appuser:appuser storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

USER appuser

EXPOSE 9000
CMD ["php-fpm"]

# ---------------------------------------------------------------------------
# Stage 3: Nginx, serving public/ and proxying PHP requests to app:9000.
# ---------------------------------------------------------------------------
FROM nginx:1.27-alpine AS web

COPY docker/nginx.conf /etc/nginx/conf.d/default.conf
COPY --from=app /var/www/public /var/www/public

EXPOSE 80
