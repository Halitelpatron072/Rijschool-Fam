FROM php:8.2-apache

# Install required PHP extensions once at build time (no runtime compilation).
RUN set -eux; \
    docker-php-ext-install -j"$(nproc)" pdo pdo_mysql mysqli; \
    a2enmod rewrite; \
    rm -rf /var/lib/apt/lists/*

# Optional: set a sane working directory (Apache serves /var/www/html by default)
WORKDIR /var/www/html
