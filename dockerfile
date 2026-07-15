# Start from an existing image that already has PHP + php-fpm installed.
FROM php:8.4-fpm

# Set the "current folder" inside the container.
WORKDIR /var/www

# Install system tools and PHP extensions our app needs.
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libzip-dev \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo_mysql zip gd

# Grab the composer program from its own official image.
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Create a regular (non-root) user to run our app as.
# Why: if the app is ever compromised, an attacker only gets this
# limited user's permissions, not full control of the container.
RUN useradd -m -u 1000 -s /bin/bash appuser

# Copy our code in, and make sure appuser actually owns it
# (not root, since root created these files by default).
COPY --chown=appuser:appuser . .

# Install our app's PHP dependencies.
RUN composer install --no-interaction --prefer-dist

# storage/ and bootstrap/cache/ need to be writable while the app runs
# (logs, cached views, etc get written there).
RUN chown -R appuser:appuser storage bootstrap/cache

# From here on, run everything as appuser instead of root.
USER appuser

# This container listens on port 9000 (nginx forwards PHP requests here).
EXPOSE 9000

CMD ["php-fpm"]
