FROM php:8.2-fpm-alpine3.20

RUN apk add --no-cache curl git build-base zlib-dev oniguruma-dev autoconf bash libpq-dev --update linux-headers

RUN docker-php-ext-install opcache mysqli pdo_mysql pdo_pgsql mbstring

COPY php.ini /usr/local/etc/php/php.ini
COPY opcache.ini /usr/local/etc/php/conf.d/opcache.ini

ARG PUID=1000
ARG PGID=1000
RUN apk --no-cache add shadow && \
    groupmod -o -g ${PGID} www-data && \
    usermod -o -u ${PUID} -g www-data www-data

WORKDIR /var/www/html

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

EXPOSE 9000

CMD php-fpm