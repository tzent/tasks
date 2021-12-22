FROM php:8.1-cli-buster
MAINTAINER Tzvetelin Tzvetkov "tzvetelin.tzvetkov@gmail.com"

RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install -y --no-install-recommends \
        curl \
        git \
        unzip \
        libzip-dev \
        librabbitmq-dev \
        wait-for-it \
        supervisor && \
    pecl install redis amqp-1.11.0 && \
    docker-php-ext-install zip mysqli pdo_mysql && \
    docker-php-ext-enable redis amqp && \
    apt-get autoclean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

COPY conf.d/supervisord.conf /etc/supervisor/supervisord.conf
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN curl -L https://cs.symfony.com/download/php-cs-fixer-v2.phar -o php-cs-fixer && \
    chmod a+x php-cs-fixer && \
    mv php-cs-fixer /usr/local/bin/php-cs-fixer

WORKDIR /usr/src/app
ENTRYPOINT ["./entrypoint.sh"]
