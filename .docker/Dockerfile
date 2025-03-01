FROM php:8.2-fpm-alpine3.21

WORKDIR /var/www

RUN apk update && apk add --no-cache \
    nginx \
    bash && \
    rm -rf /var/cache/apk/*

COPY .docker/config/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY .docker/config/nginx/nginx.conf /etc/nginx/nginx.conf
COPY .docker/config/php/www.conf /usr/local/etc/php-fpm.conf
COPY .docker/config/php/php.ini /usr/local/etc/php/conf.d/php.ini

RUN rm -rf /var/www/html \
           /var/www/localhost && \
    mkdir -p /var/run/php && \
    chown -R www-data:www-data /var/run/php \
                               /var/lib/nginx

CMD ["sh", "-c", "php-fpm --nodaemonize & nginx -g 'daemon off;'"]
