ARG APACHE_VERSION=2.4

FROM httpd:latest

WORKDIR /Users/nykianderson/Sites/shape-share

EXPOSE 80
EXPOSE 443

RUN apt-get update -yqq && apt-get install -yqq 

COPY /sites-available/my-httpd.conf /usr/local/apache2/conf/httpd.conf

RUN sed -i "s/#LoadModule\ rewrite_module/LoadModule\ rewrite_module/" /usr/local/apache2/conf/httpd.conf;

# Copy apache vhost file to proxy php requests to php-fpm container
COPY /sites-available/demo.apache.conf /usr/local/apache2/conf/demo.apache.conf

COPY /sites-available/my-httpd.conf /usr/local/apache2/conf/httpd.conf

RUN echo "Include /usr/local/apache2/conf/my-httpd.conf" \
    >> /usr/local/apache2/conf/my-httpd.conf

COPY /sites-available/default.conf /usr/local/apache2/conf/default.conf

RUN echo "Include /usr/local/apache2/conf/default.conf" \
    >> /usr/local/apache2/conf/default.conf

RUN echo "Include /usr/local/apache2/conf/demo.apache.conf" \
    >> /usr/local/apache2/conf/demo.apache.conf

COPY sites-available/shape-share.conf /usr/local/apache2/conf/shape-share.conf

RUN echo "Include /usr/local/apache2/conf/shape-share.conf" \
    >> /usr/local/apache2/conf/shape-share.conf

ENV ServerName=local.shape-share.com

RUN rm -rf /var/www/local.shape-share.com/public
CMD exec /usr/sbin/httpd -D FOREGROUND -f /etc/apache





