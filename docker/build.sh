#!/bin/bash
set -e

if ! [[ -d ../tmp/Logs/apache ]];
then 
  mkdir -p ../tmp/Logs/apache
fi

if ! [[ -d ../tmp/Logs/mariadb ]];
then 
  mkdir -p ../tmp/Logs/mariadb
fi

if ! [[ -d ../tmp/Logs/sphp ]];
then 
  mkdir -p ../tmp/Logs/php
fi

if ! [[ -d ../database/mariadb ]];
then 
  mkdir -p ../database/mariadb
fi

docker-compose up -d --build

docker exec shape_share_apache_con chown -R root:www-data /usr/local/apache2/Logs

docker exec shape_share_php_con chown -R root:www-data /usr/local/etc/Logs