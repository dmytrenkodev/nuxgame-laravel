FROM php:8.2-fpm-alpine

RUN apk add --no-cache \
    bash \
    git \
    build-base \
    mysql-client \
    autoconf \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-enable pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

RUN chown -R www-data:www-data /var/www/html

EXPOSE 9000

CMD ["php-fpm"]