# syntax=docker/dockerfile:1
FROM php:8.2-cli

ARG WWWUSER=1000
ARG WWWGROUP=1000

# Install system dependencies and PHP extensions required by Laravel
RUN apt-get update \
    && apt-get install -y \
        git \
        unzip \
        libzip-dev \
        libicu-dev \
        sqlite3 \
        libsqlite3-dev \
        libonig-dev \
        curl \
    && docker-php-ext-install \
        intl \
        pdo \
        pdo_mysql \
        pdo_sqlite \
        zip \
        pcntl \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer (shared cache layer with official image)
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Create a non-root user that matches the host user/group when provided
RUN groupadd --force --gid ${WWWGROUP} sail \
    && useradd --uid ${WWWUSER} --gid sail --shell /bin/bash --create-home sail

WORKDIR /var/www/html

# Copy entrypoint script
COPY docker/entrypoint.sh /usr/local/bin/start-app
RUN chmod +x /usr/local/bin/start-app

USER sail

EXPOSE 8000

ENTRYPOINT ["start-app"]
