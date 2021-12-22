#!/bin/bash

set -e

composer install --no-interaction
composer dump-autoload
bin/console cache:clear
bin/console doctrine:migrations:migrate --no-interaction
php-fpm