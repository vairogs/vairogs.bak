#!/bin/sh

vendor/bin/php-cs-fixer fix

rm -rf tests/var/cache/*

vendor/bin/phpunit

rm -rf tests/var
