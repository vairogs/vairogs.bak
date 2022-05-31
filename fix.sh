#!/bin/sh

vendor/bin/php-cs-fixer fix src
vendor/bin/php-cs-fixer fix tests/assets
vendor/bin/php-cs-fixer fix tests/config
vendor/bin/php-cs-fixer fix tests/entity
vendor/bin/php-cs-fixer fix tests/src

rm -rf tests/var/cache/*
rm -rf src/Vairogs/Common/var/*

vendor/bin/phpunit
