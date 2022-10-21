#!/bin/bash

# Create the server-side certificates
OPENSSL_SERVER=$1

docker run --rm -v $PWD/.security/ssl/certs/mysql:/certs/mysql -it nginx \
  openssl req -newkey rsa:2048 -days 3600 -nodes \
    -subj "${OPENSSL_SERVER}" \
    -keyout /certs/mysql/server-key.pem -out /certs/server-req.pem

docker run -rm -v $PWD/.security/ssl/certs/mysql:certs/mysql -it nginx \
  openssl rsa -in /certs/mysql/server-key.pem -out /certs/server-key.pem

docker run --rm -v $PWD/.security/ssl/certs/mysql:/certs/mysql -it nginx \
  openssl x509 -req -in /certs/mysql/server-req.pem -days 3600 \
    -CA /certs/root-ca.pem -CAkey /certs/root-ca-key.pem \
    -set_serial 01 -out /certs/server/server-cert.pem

# Verify the certificates are correct
docker run --rm -v $PWD/.security/ssl/certs/mysql/:/certs/mysql/ -it nginx \
  openssl verify -CAfile /certs/root-ca.pem /certs/mysql/server-cert.pem

   