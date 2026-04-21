FROM php:8.3-fpm-alpine

WORKDIR /var/www/html

RUN apk add --no-cache \
    bash \
    git \
    icu-dev \
    libzip-dev \
    mysql-client \
    oniguruma-dev \
    unzip \
    zip \
    && docker-php-ext-configure intl \
    && docker-php-ext-install -j$(nproc) pdo_mysql intl zip opcache \
    && rm -rf /tmp/* /var/cache/apk/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY ./docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY ./docker/php/php.ini /usr/local/etc/php/conf.d/app.ini

COPY . /var/www/html

RUN addgroup -g 1000 -S app \
    && adduser -u 1000 -S app -G app \
    && chown -R app:app /var/www/html

USER app

