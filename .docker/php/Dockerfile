FROM php:8.0-fpm-alpine

EXPOSE 9000 90

# install extensions
RUN apk add $PHPIZE_DEPS \ 
    zsh \
    bash \
    git \
    tzdata 

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
    opcache \
    zip \
  ; \
  \
  runDeps="$( \
      scanelf --needed --nobanner --format '%n#p' --recursive /usr/local/lib/php/extensions \
          | tr ',' '\n' \
          | sort -u \
          | awk 'system("[ -e /usr/local/lib/" $1 " ]") == 0 { next } {print "so:" $1 }' \
  )"; \
  apk add --virtual .phpmyadmin-phpexts-rundeps $runDeps; \
  apk del --no-network .build-deps

# Calculate download URL
ENV VERSION 5.2.0
ENV SHA256 f141a5b493136b627bd910facd43a2d2203cccd9a6579566e4e323da8ccc03ba
ENV URL https://files.phpmyadmin.net/phpMyAdmin/${VERSION}/phpMyAdmin-${VERSION}-english.tar.xz

RUN set -ex; \
    apk add --no-cache --virtual .fetch-deps \
        gnupg \
    ; \
    \
    export GNUPGHOME="$(mktemp -d)"; \
    export GPGKEY="3D06A59ECE730EB71B511C17CE752F178259BD92"; \
    curl -fsSL -o phpMyAdmin.tar.xz $URL; \
    curl -fsSL -o phpMyAdmin.tar.xz.asc $URL.asc; \
    echo "$SHA256 *phpMyAdmin.tar.xz" | sha256sum -c -; \
    gpg --batch --keyserver keyserver.ubuntu.com --recv-keys "$GPGKEY" \
        || gpg --batch --keyserver pgp.mit.edu --recv-keys "$GPGKEY" \
        || gpg --batch --keyserver keyserver.pgp.com --recv-keys "$GPGKEY" \
        || gpg --batch --keyserver keys.openpgp.org --recv-keys "$GPGKEY"; \
    gpg --batch --verify phpMyAdmin.tar.xz.asc phpMyAdmin.tar.xz; \
    tar -xf phpMyAdmin.tar.xz -C /var/www/html --strip-components=1; \
    mkdir -p /var/www/html/tmp; \
    chown www-data:www-data /var/www/html/tmp; \
    gpgconf --kill all; \
    rm -r "$GNUPGHOME" phpMyAdmin.tar.xz phpMyAdmin.tar.xz.asc; \
    rm -r -v /var/www/html/setup/ /var/www/html/examples/ /var/www/html/js/src/ /var/www/html/templates/test/ /var/www/html/babel.config.json /var/www/html/doc/html/_sources/ /var/www/html/RELEASE-DATE-$VERSION /var/www/html/CONTRIBUTING.md; \
    grep -q -F "'configFile' => ROOT_PATH . 'config.inc.php'," /var/www/html/libraries/vendor_config.php; \
    sed -i "s@'configFile' => .*@'configFile' => '/etc/phpmyadmin/config.inc.php',@" /var/www/html/libraries/vendor_config.php; \
    grep -q -F "'configFile' => '/etc/phpmyadmin/config.inc.php'," /var/www/html/libraries/vendor_config.php; \
    php -l /var/www/html/libraries/vendor_config.php; \
    apk del --no-network .fetch-deps

RUN pecl install xdebug \
RUN docker-php-ext-enable xdebug

# install composer
COPY --from=composer:2.4.4 /usr/bin/composer /usr/bin/composer

COPY ./conf.d/*.ini /etc/php/

COPY ./conf.d/config.inc.php /etc/phpmyadmin/config.inc.php

COPY "${APP_SRC}*" "${DOCKER_APP_ROOT}"

VOLUME ["./conf.d/:${DOCKER_PHP_CONFIG}"]
VOLUME ["./log/:${DOCKER_PHP_LOGS}"]
VOLUME ["${APP_SRC}:${DOCKER_APP_ROOT}"]

RUN chmod +x php.sh
# Copy main script
COPY php.sh /php.sh

RUN chmod +x /php.sh

ENTRYPOINT [ "/php.sh" ]
CMD ["php-fpm"]