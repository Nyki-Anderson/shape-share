#!/bin/bash

# Create the client-side certificates
OPENSSL_CLIENT=$1

docker run --rm -v $PWD/.security/ssl/certs/mysql/:/certs/mysql/ -it nginx \
  openssl req -newkey rsa:2048 -days 3600 -nodes \
    -subj "${OPENSSL_CLIENT}" \
    -keyout /certs/mysql/client-key.pem -out /certs/mysql/client-req.pem

docker run --rm -v $PWD/.security/ssl/certs/mysql/:/certs/mysql -it nginx \
  openssl rsa -in /certs/mysql/client-key.pem -out /certs/mysql/client-key.pem

docker run --rm -v $PWD/.security/ssl/certs/mysql/:/certs/mysql -it nginx \
  openssl x509 -req -in /certs/mysql/client-req.pem -days 3600 \
    -CA /certs/root-ca.pem -CAkey /certs/root-ca-key.pem \
    -set_serial 01 -out /certs/mysql/client-cert.pem

# Verify the certificates are correct
docker run --rm -v $PWD/.security/ssl/certs/mysql/:/certs/client -it nginx \
  openssl verify -CAfile /certs/root-ca.pem /certs/mysql/client-cert.pem