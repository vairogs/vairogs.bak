<?php declare(strict_types = 1);

namespace Vairogs\Extra\Constants\Enum\Traits;

use function array_map;
use function property_exists;

trait CasesTrait
{
    public static function getCases(): array
    {
        if (!property_exists(object_or_class: self::class, property: 'value')) {
            return [];
        }

        return array_map(static fn ($enum) => $enum->value, self::cases());
    }
}
