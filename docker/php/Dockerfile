ARG PHP_VERSION="8.2"

FROM php:latest

WORKDIR /Users/nykianderson/Sites/shape-share/

CMD [ "php", "shape-share/public_html/index.php" ]

RUN docker-php-ext-install pdo pdo_mysql

RUN apt-get update && apt-get install -y \
		libfreetype6-dev \
		libjpeg62-turbo-dev \
		libpng-dev \
	&& docker-php-ext-configure gd --with-freetype --with-jpeg \
	&& docker-php-ext-install -j$(nproc) gd