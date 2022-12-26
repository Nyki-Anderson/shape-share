FROM httpd:alpine3.16

WORKDIR /

# install common dev tools
RUN apk upgrade 

RUN apk add --no-cache \
    #ca-certificates \
    git \
    gcc \
    musl-dev \
    openssl \
    apache2-ssl \
    apache2-proxy \
    certbot-apache \
    php8-fpm \
    vim 

RUN rm -rf /var/www/localhost/cgi-bin/ \
    /var/cache/apk/* \
    /tmp/* \
    /sbin/halt \
    /sbin/poweroff \
    /sbin/reboot \
    /etc/init.d/*;

COPY ./ssl/local.shape-share.com.ext /etc/ssl/certs/local.shape-share.com.ext

#RUN update-ca-certificates

RUN openssl genrsa -out /etc/ssl/certs/myCA.key 2048 \
    && openssl req -x509 -new -nodes -key /etc/ssl/certs/myCA.key -sha256 -days 3650 -out /etc/ssl/certs/myCA.pem \
    -subj "/C=US/ST=VA/L=Herndon/O=Shape Share Ltd. y/OU=/CN=local.shape-share.com" \
    && openssl genrsa -out /etc/ssl/certs/local.shape-share.com.key 2048 \
    && openssl req -new -key /etc/ssl/certs/local.shape-share.com.key -out /etc/ssl/certs/local.shape-share.com.csr \
    -subj "/C=US/ST=VA/L=Herndon/O=Shape Share Ltd. y/OU=/CN=local.shape-share.com" \
    && openssl x509 -req -in /etc/ssl/certs/local.shape-share.com.csr -CA /etc/ssl/certs/myCA.pem -CAkey /etc/ssl/certs/myCA.key -CAcreateserial \
    -out /etc/ssl/certs/local.shape-share.com.csr -days 3650 -sha256 -extfile /etc/ssl/certs/local.shape-share.com.ext 
    
#RUN cp /etc/ssl/certs/local* /usr/local/share/ca-certificates/

#RUN update-ca-certificates

COPY ./httpd.conf /etc/apache2/httpd.conf
COPY ./conf.d/* /etc/apache2/conf.d
COPY ${APP_ROOT} /var/www/shape-share/

VOLUME ["./httpd.conf:/etc/apache2/httpd.conf"]
VOLUME ["./conf.d/:/etc/apache2/conf.d/"]
VOLUME ["./logs/:/var/apache2/logs/"]
VOLUME ["$APP_ROOT:/var/www/shape-share/"]

WORKDIR /var/www/shape-share/
#RUN chown -R www-data:www-data public_html/

EXPOSE 80 8081 443

CMD ["httpd", "-D", "FOREGROUND"]

