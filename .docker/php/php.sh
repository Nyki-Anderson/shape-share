#!/bin/bash

if [[ "$1" == apache2* ]] || [ "$1" == php-fpm ]; then

  if [ ! -f "${PHPMYADMIN_CONFIG_SECRET}" ]; then
      cat > "${PHPMYADMIN_CONFIG_SECRET}" <<EOT

<?php
\$cfg['blowfish_secret'] = '$(tr -dc 'a-zA-Z0-9~!@#$%^&*_()+}{?></";.,[]=-' < /dev/urandom | fold -w 32 | head -n 1)';
EOT
  fi

  if [ ! -f "${DOCKER_PHPMYADMIN_CONFIG}" ]; then
      touch "${DOCKER_PHPMYADMIN_CONFIG}"
  fi
fi

if [ ! -z "${PMA_CONFIG_BASE64}" ]; then
    echo "Adding the custom config.user.inc.php from base64."
    echo "${PMA_USER_CONFIG_BASE64}" | base64 -d > "${DOCKER_PHPMYADMIN_CONFIG}"
fi

get_docker_secret() {
  local env_var="${1}"
  local env_var_file="${env_var}_FILE"

  # Check if the variable with name $env_var_file 
  # is not empty and export $PMA_PASSWORED as the 
  # password in teh Docker secrets file
  if [[ -n "${!env_var_file}" ]]; then
      export "${env_var}"="$(cat "${!env_var_file}")"
  fi
}

get_docker_secret PMA_USER
get_docker_secret PMA_PASSWORD
get_docker_secret MYSQL_ROOT_PASSWORD
get_docker_secret MYSQL_PASSWORD
get_docker_secret PMA_HOSTS
get_docker_secret PMA_HOST
get_docker_secret PMA_CONTROLHOST
get_docker_secret PMA_CONTROLUSER
get_docker_secret PMA_CONTROLPASS

exec "$@"