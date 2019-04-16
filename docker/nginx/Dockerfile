FROM debian:jessie

RUN apt-get update && apt-get install -y \
    nginx

COPY nginx.conf /etc/nginx/
COPY nginx_start.sh /etc/nginx/

RUN rm /etc/nginx/sites-enabled/default

RUN echo "upstream php-upstream { server php:9000; }" > /etc/nginx/conf.d/upstream.conf

RUN usermod -u 1000 www-data

ENTRYPOINT /etc/nginx/nginx_start.sh

EXPOSE 80
EXPOSE 443
