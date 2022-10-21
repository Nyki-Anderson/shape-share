#!/bin/bash

mkdir -p certs/mysql

OPENSSL_SUBJ="/C=US/ST=Virginia/L=Herndon"
OPENSSL_CA="${OPENSSL_SUBJ}/CN=mysql-CA"
OPENSSL_SERVER="${OPENSSL_SUBJ}/CN=mysql-server"
OPENSSL_CLIENT="${OPENSSL_SUBJ}/CN=mysql-client"

sh ./genroot.sh "${OPENSSL_CA}"
sh ./genserver.sh "${OPENSSL_SERVER}"
sh ./genclient.sh "${OPENSSL_CLIENT}"