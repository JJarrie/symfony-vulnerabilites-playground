FROM php:8.4-fpm-alpine AS php_base

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN apk add --no-cache bash

RUN install-php-extensions apcu opcache pdo_pgsql intl zip

WORKDIR /srv/app

FROM php_base AS php_dev

RUN install-php-extensions xdebug

CMD ["php-fpm"]
