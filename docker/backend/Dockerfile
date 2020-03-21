FROM composer:1.7.2 as build-stage
COPY ./backend /app
RUN composer update

FROM php:7.3-fpm
RUN apt-get update; \
    apt-get install -y libpng-dev; \
    docker-php-ext-install gd \
    pdo \
    pdo_mysql
RUN apt-get -y install nginx \
    supervisor
COPY ./docker/nginx/sites-enabled-back /etc/nginx/conf.d/default.conf
COPY --from=build-stage /app /home/backend
COPY ./docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY ./docker/supervisor/supervisord.conf /etc/supervisor/supervisord.conf

RUN chown -R :www-data /home/backend/storage/logs/
RUN chown -R :www-data /home/backend/storage/
RUN chmod -R g+w /home/backend/storage
RUN cd /home/backend; php artisan key:generate

EXPOSE 80

CMD ["service", "supervisor", "start"]