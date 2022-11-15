#!/bin/sh

# execute any pre-init scripts
for i in /scripts/pre-init.d/*sh
do
    if [ -e "${i}" ]; then
        echo "[i] pre-init.d - processing $i" . "${i}"
    fi
done

if [ "$SERVER_KEY" ]; then
    echo "$SERVER_KEY" | sed "s/\\$/\n/g" | sed "s/^ //g" > /etc/mysql/server.key
    echo "$SERVER_CERT" | sed "s/\\$/\n/g" | sed "s/^ //g" > /etc/mysql/server.crt
    echo "$CA_CERT" | sed "s/\\$/\n/g" | sed "s/^ //g" > /etc/mysql/CA.crt

    export MYSQLD_SSL_KEY=/etc/mysql/server.key
    export MYSQLD_SSL_CERT=/etc/mysql/server.crt
    export MYSQLD_SSL_CA=/etc/mysql/CA.crt
fi

exec /usr/bin/mysqld --user=mysql --console