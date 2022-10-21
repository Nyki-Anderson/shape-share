FROM mysql/mysql-server:8.0.30

LABEL description="Initialize Database"

# Initialize Database
COPY ./init_scripts/dev_db_instance.sql /docker-entrypoint-initdb.d/initdb.sql

# Configuration Files
COPY ./my.cnf.d/client_ssl.cnf ./my.cnf.d/server_ssl.cnf ./my.cnf.d/encrypt.cnf ./my.cnf.d/my.cnf  /etc/mysql/my.cnf.d/