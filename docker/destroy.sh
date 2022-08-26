#!/bin/bash
set -e

docker-compose down --volumes
docker rmi shape-share_apache_img shapeshare_php_img