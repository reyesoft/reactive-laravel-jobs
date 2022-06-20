#!/bin/sh

## preparing to cs-fixer v3
PHP_CS_FIXER_FUTURE_MODE=1 ./vendor/bin/php-cs-fixer fix \
    --config=./resources/rules/php-cs-fixer.php \
    --dry-run --stop-on-violation &&

exit $?
