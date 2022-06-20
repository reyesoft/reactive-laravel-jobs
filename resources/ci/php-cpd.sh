#!/bin/sh

# --regexps-exclude=IntegrationTests \
./vendor/bin/phpcpd --min-tokens=50 ./src/ \
--exclude=FiscalbookExport.php,FiscalbookTrait.php \
&&

exit $?
