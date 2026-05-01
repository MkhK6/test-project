ARG PHP_IMAGE_TAG=8.4-fpm-alpine
ARG COMPOSER_IMAGE_TAG=2
ARG MLOCATI_INSTALLER_IMAGE_TAG=2

FROM composer:${COMPOSER_IMAGE_TAG} AS composer_bin
FROM mlocati/php-extension-installer:${MLOCATI_INSTALLER_IMAGE_TAG} AS php_extension_installer

FROM php:${PHP_IMAGE_TAG} AS base

WORKDIR /var/www/html

RUN apk add --no-cache bash git postgresql-client unzip

COPY --from=php_extension_installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions pdo_pgsql opcache

COPY --from=composer_bin /usr/bin/composer /usr/bin/composer
COPY docker/php/php-fpm.conf /usr/local/etc/php-fpm.d/zz-app.conf

FROM base AS dev
RUN install-php-extensions xdebug
COPY docker/php/php.ini /usr/local/etc/php/conf.d/zz-app.ini
COPY docker/php/xdebug.ini /usr/local/etc/php/conf.d/zz-xdebug.ini

FROM base AS prod
COPY docker/php/php-prod.ini /usr/local/etc/php/conf.d/zz-app.ini
COPY src/composer.json src/composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --classmap-authoritative --optimize-autoloader
COPY src/ /var/www/html/
RUN mkdir -p storage/logs \
        storage/framework/cache \
        storage/framework/sessions \
        storage/framework/views \
        bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache
