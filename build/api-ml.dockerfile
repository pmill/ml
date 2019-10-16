FROM php:7-fpm-alpine3.10

RUN docker-php-ext-configure opcache --enable-opcache
RUN pecl install apcu apcu_bc
RUN docker-php-ext-install opcache mysqli pdo pdo_mysql
RUN docker-php-ext-enable apcu