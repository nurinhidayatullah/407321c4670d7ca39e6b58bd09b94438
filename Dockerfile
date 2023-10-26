FROM composer:latest as composer
RUN rm -rf /var/www && mkdir -p /var/www/html
WORKDIR /var/www/html
COPY ./src/composer.json ./src/composer.lock ./

RUN composer install --ignore-platform-reqs --prefer-dist --no-scripts --no-progress --no-interaction --no-dev --no-autoloader
RUN composer update --ignore-platform-reqs --prefer-dist --no-scripts --no-progress --no-interaction --no-dev --no-autoloader
RUN composer dump-autoload --optimize --apcu --no-dev

FROM php:8.2-fpm-alpine as base

RUN  --mount=type=bind,from=mlocati/php-extension-installer:1.5,source=/usr/bin/install-php-extensions,target=/usr/local/bin/install-php-extensions
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions sockets pdo_pgsql pgsql

COPY src src/

CMD ["php-fpm"]