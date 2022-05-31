<?php declare(strict_types = 1);

use Doctrine\DBAL\Types\Types;
use Symfony\Config\DoctrineConfig;
use Vairogs\Extra\Constants\Definition;

return static function (DoctrineConfig $config): void {
    $dbal = $config
        ->dbal()
        ->connection(name: $default = Definition::DEFAULT);

    $dbal
        ->url(value: '%env(resolve:TEST_DATABASE_URL)%')
        ->serverVersion(value: 14)
        ->charset(value: $charset = 'utf8');

    $dbal
        ->defaultTableOption(name: 'charset', value: $charset)
        ->defaultTableOption(name: 'collate', value: 'utf8mb4_unicode_ci')
        ->mappingType(name: 'enum', value: Types::STRING)
        ->mappingType(name: 'set', value: Types::STRING)
        ->mappingType(name: 'varbinary', value: Types::STRING);

    $em = $config
        ->orm()
        ->defaultEntityManager(value: $default)
        ->autoGenerateProxyClasses(value: false)
        ->entityManager(name: $default);

    $em
        ->namingStrategy(value: 'doctrine.orm.naming_strategy.underscore_number_aware')
        ->autoMapping(value: true);

    $em
        ->mapping(name: 'Vairogs')
        ->isBundle(value: false)
        ->type(value: 'attribute')
        ->alias(value: 'Vairogs')
        ->prefix(value: 'Vairogs\Entity')
        ->dir(value: '%kernel.project_dir%/entity');
};
