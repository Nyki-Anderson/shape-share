FROM mariadb:10.8.3

ARG MARIADB_CONTAINER_USER
ARG MARIADB_CONTAINER_GROUP

RUN sed -i "s/#log-error/log-error/g" /etc/mariadb/mariadb.conf.d/mysqld.cnf

RUN usermod -u 1000 ${MARIADB_CONTAINER_USER} \
  && groupmod -g 1000 ${MARIADB_CONTAINER_GROUP}
