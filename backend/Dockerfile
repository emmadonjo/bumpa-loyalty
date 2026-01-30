FROM php:8.2-fpm

# System dependencies and php extension
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    supervisor \
    && docker-php-ext-install \
        pdo_mysql \
        mbstring \
        bcmath \
        intl \
        zip \
        opcache \
        exif \
        pcntl \
        bcmath \
        gd \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Composer (copied, not installed)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy only composer files first (layer caching)
COPY composer.json composer.lock ./

# Copy application
COPY . .

RUN composer install --no-interaction --prefer-dist -o

RUN mkdir -p /var/log/supervisor

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

USER www-data

COPY docker/supervisor/supervisord.conf /etc/supervisor/supervisord.conf
COPY docker/supervisor/*.conf /etc/supervisor/conf.d/

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]

EXPOSE ${BACKEND_PORT:-8000}


COPY prod.sh /usr/local/bin/prod.sh
RUN chmod +x /usr/local/bin/prod.sh
ENTRYPOINT ["/usr/local/bin/prod.sh"]
