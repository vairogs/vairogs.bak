<?php declare(strict_types = 1);

namespace Vairogs\Tests\Assets\Utils\Helper\Model;

class Entity7
{
    use ExtraVariablesTrait;

    protected static string $value = 'value';

    public static function getStaticValue(): string
    {
        return self::$value;
    }
}
