FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    libzip-dev \
    unzip
RUN docker-php-ext-install zip

COPY app /usr/src/app

WORKDIR /usr/src/app
VOLUME /usr/src/app

ENV COMPOSER_ALLOW_SUPERUSER 1
COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN composer self-update

ENTRYPOINT ["docker-php-entrypoint"]

CMD ["php", "-a"]
