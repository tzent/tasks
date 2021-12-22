FROM php:8.1-fpm-buster
MAINTAINER Tzvetelin Tzvetkov "tzvetelin.tzvetkov@gmail.com"

RUN DEBIAN_FRONTEND=noninteractive apt-get update && \
    apt-get upgrade -y && \
    apt-get install -y --no-install-recommends \
        libssl-dev \
        zlib1g-dev \
        libev-dev \
        git \
        unzip \
        librabbitmq-dev \
        libxml2-dev \
        libzip-dev \
        wait-for-it \
        supervisor && \
    pecl install redis amqp-1.11.0 && \
    docker-php-ext-install -j$(nproc) zip opcache intl bcmath mysqli pdo_mysql && \
    docker-php-ext-enable redis amqp && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

COPY conf.d/supervisord.conf /etc/supervisor/supervisord.conf
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN curl -L https://cs.symfony.com/download/php-cs-fixer-v2.phar -o php-cs-fixer && \
    chmod a+x php-cs-fixer && \
    mv php-cs-fixer /usr/local/bin/php-cs-fixer

WORKDIR /var/www/html
ENTRYPOINT ["./entrypoint.sh"]
