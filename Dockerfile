FROM php:8.0-fpm-alpine

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
WORKDIR /app

EXPOSE 8000

CMD [ -d "vendor" ] && php -S 0.0.0.0:8000 || composer install && php -S 0.0.0.0:8000
