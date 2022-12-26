FROM php:8.0-fpm-alpine

EXPOSE 9000 90

RUN mkdir -p /var/lib/php/session \
    && chmod 777 /var/lib/php/session

COPY ./conf.d/*.ini ./conf.d/*.conf /etc/php/
COPY ./conf.d/config.inc.php /etc/phpmyadmin/config.inc.php
COPY "${APP_ROOT}" /var/www/shape-share/

VOLUME ["./conf.d/:/etc/php/conf.d/"]
VOLUME ["./log/:/var/www/logs/php/"]
VOLUME ["${APP_ROOT}:/var/www/shape-share/"]

# install extensions
RUN apk add $PHPIZE_DEPS \ 
    zsh \
    bash \
    git \
    tzdata \
    linux-headers

RUN set -ex; \
    \
    apk add --no-cache --virtual .build-deps \
    bzip2-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libwebp-dev \
    libxpm-dev \
    libzip-dev \
  ; \
  \
  docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp --with-xpm && \
  docker-php-ext-install \
    pdo \
    pdo_mysql \
    mysqli \
    opcache \
    zip;

RUN pecl install xdebug \
RUN docker-php-ext-enable xdebug

# install composer
COPY --from=composer:2.4.4 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/shape-share/
USER www-data

CMD ["php-fpm"]