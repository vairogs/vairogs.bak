#!/bin/sh

vendor/bin/php-cs-fixer --config=vendor/spaghetti/php-cs-fixer-config/.php-cs-fixer.php fix

rm -rf tests/var/cache/*

vendor/bin/phpunit

rm -rf tests/var
