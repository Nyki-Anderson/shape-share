FROM php:8.1-apache

# Update OS and install common dev tools
RUN apt-get update
RUN apt-get install -y zip unzip libzip-dev libpng-dev libicu-dev ca-certificates

# Install PHP extensions needed
RUN docker-php-ext-install mysqli pdo_mysql 
RUN pecl install xdebug 
RUN docker-php-ext-enable

# Enable common Apache modules
RUN a2enmod headers expires rewrite

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

RUN composer global require friendsofphp/php-cs-fixer

# Set working directory to web files
WORKDIR /var/www