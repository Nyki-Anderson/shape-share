FROM mariadb:10.9

RUN apt-get update \
    && apt-get install mariadb-client pwgen \
    && rm -rf /var/lib/apt/lists/*

# Configuration Files
COPY ./my.cnf.d/*.cnf /etc/mysql/my.cnf.d/

EXPOSE 3306

VOLUME ["./mysql_data/:${DOCKER_MYSQL_DATA}"]
VOLUME ["./my.cnf.d/:${DOCKER_MYSQL_CONFIG}"]
VOLUME ["./ssl/:${DOCKER_MYSQL_SSL}"]
VOLUME ["./log/:${DOCKER_MYSQL_LOGS}"]

RUN chmod +x db.sh
COPY db.sh /scripts/db.sh
RUN chmod +x db.sh
ENTRYPOINT ["/scripts/run.sh"]
