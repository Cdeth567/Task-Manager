FROM php:8.3-cli

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN apt-get update \
    && apt-get install -y --no-install-recommends git unzip libpq-dev libicu-dev \
    && docker-php-ext-install pdo_pgsql intl \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY composer.json ./
RUN composer install --no-interaction --prefer-dist --no-scripts --no-progress

COPY . .
RUN composer dump-autoload --classmap-authoritative --no-interaction

CMD ["php", "-S", "0.0.0.0:8000", "-t", "public", "public/index.php"]
