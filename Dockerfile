FROM composer as builder
WORKDIR /app/
COPY src composer.* ./
RUN composer install && composer dump-autoload

FROM php:8.1-apache

# Install php-mysql driver
RUN docker-php-ext-install mysqli pdo pdo_mysql

WORKDIR /app/

COPY --from=builder /app/vendor /app/vendor

COPY . .

ENV APACHE_DOCUMENT_ROOT /app/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
