#!/bin/bash

set -e

wait-for-it mq-broker:5672 --strict --timeout=30 --
composer install --no-interaction
composer dump-autoload
bin/console cache:clear
bin/console doctrine:migrations:migrate --no-interaction
bin/console messenger:setup-transports
/usr/bin/supervisord -n -c /etc/supervisor/supervisord.conf

