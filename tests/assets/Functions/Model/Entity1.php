<?php declare(strict_types = 1);

namespace Vairogs\Tests\Assets\Functions\Model;

use Vairogs\Tests\Assets\Utils\Doctrine\Traits\Entity;

class Entity1 extends Entity
{
    use ExtraVariablesTrait;

    protected static string $value = 'value';

    public static function getStaticValue(): string
    {
        return self::$value;
    }
}
