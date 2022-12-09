<?php declare(strict_types = 1);

namespace Vairogs\Functions\Tests\Assets\Model;

class Entity7 extends Entity
{
    use Traits\ExtraVariables;

    protected static string $value = 'value';

    public static function getStaticValue(): string
    {
        return self::$value;
    }
}
