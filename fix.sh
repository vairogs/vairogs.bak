#!/bin/sh

vendor/bin/php-cs-fixer --config=src/Vairogs/Config/.php-cs-fixer.php fix

rm -rf tests/var/cache/*

vendor/bin/phpunit

rm -rf tests/var
