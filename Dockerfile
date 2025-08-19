FROM php:8.2-cli

# Instalar extensiones necesarias
RUN apt-get update && apt-get install -y \
    unzip \
    curl \
    zip \
    git \
    libzip-dev \
    && docker-php-ext-install zip pdo pdo_mysql  pdo pdo_mysql

# Instalar Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer
