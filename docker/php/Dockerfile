FROM php:8.2-fpm

ARG WEB_USER
ARG WEB_GROUP
ARG PHP_ROOT_DIR

COPY www.conf ${PHP_ROOT_DIR}/php.fpm.d/www.conf

RUN docker-php-ext-install pdo

RUN usermod -u 1000 ${WEB_USER} \
  && groupmod -g 1000 ${WEB_GROUP} \
  && chgrp -R staff ${PHP_ROOT_DIR}php-fpm.d/www.conf