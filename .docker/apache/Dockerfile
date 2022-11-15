FROM httpd:alpine3.16

# install common dev tools
RUN apk upgrade 

RUN apk add --no-cache \
    ca-certificates \
    git \
    gcc \
    musl-dev \
    openssl \
    apache2-ssl \
    apache2-proxy \
    certbot-apache

RUN rm -rf /var/www/localhost/cgi-bin/

COPY ./conf.d/*.conf /etc/apache2/conf.d/
COPY "${APP_SRC}*" "${DOCKER_APP_ROOT}"
COPY ./ssl/server-cert.pem /etc/apache2/ssl/ssl.crt
COPY ./ssl/server-key.pem /etc/apache2/ssl/ssl.key

RUN mkdir -p /var/run/apache2/

VOLUME ["./conf.d/:${DOCKER_APACHE_CONFIG}"]
VOLUME ["./logs/:${DOCKER_APACHE_LOG}"]
VOLUME ["./ssl/:${DOCKER_APACHE_SSL}"]

EXPOSE 80 443

CMD ["httpd", "-D", "FOREGROUND"]

