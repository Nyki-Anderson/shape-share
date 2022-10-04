FROM mysql/mysql-server:8.0.30

LABEL description="Initialize Database"

# Initialize Database
COPY ./init_scripts/ /docker-entrypoint-initdb.d/

# Configuration Files
COPY /my.cnf.d/client_ssl.cnf /my.cnf.d/server_ssl.cnf /my.cnf.d/encrypt.cnf my.cnf  /etc/mysql/