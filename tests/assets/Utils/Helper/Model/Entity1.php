<?php declare(strict_types = 1);

namespace Vairogs\Assets\Utils\Helper\Model;

use Vairogs\Assets\Utils\Doctrine\Traits\Entity;

class Entity1 extends Entity
{
    use ExtraVariablesTrait;

    protected static string $value = 'value';

    public static function getStaticValue(): string
    {
        return self::$value;
    }
}
