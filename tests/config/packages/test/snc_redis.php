<?php declare(strict_types = 1);

use Symfony\Config\SncRedisConfig;
use Vairogs\Core\Vairogs;

return static function (SncRedisConfig $sncRedisConfig): void {
    $sncRedisConfig
        ->client(alias: $key = 'predis')
        ->type(value: 'predis')
        ->alias(value: $key)
        ->dsns(value: [
            sprintf('%%env(REDIS_URL)%%/%s', '%env(REDIS_DB_' . strtoupper(string: $key) . ')%'),
        ])
        ->logging(value: false)
        ->options()
        ->connectionPersistent(value: true)
        ->throwErrors(value: true)
        ->prefix(value: sprintf(Vairogs::VAIROGS . '_%%env(ENVIRONMENT)%%_%s_', $key));

    $sncRedisConfig
        ->client(alias: $key = 'phpredis')
        ->type(value: 'phpredis')
        ->alias(value: $key)
        ->dsns(value: [
            sprintf('%%env(REDIS_URL)%%/%s', '%env(REDIS_DB_' . strtoupper(string: $key) . ')%'),
        ])
        ->logging(value: false)
        ->options()
        ->connectionPersistent(value: true)
        ->throwErrors(value: true)
        ->prefix(value: sprintf(Vairogs::VAIROGS . '_%%env(ENVIRONMENT)%%_%s_', $key));
};
