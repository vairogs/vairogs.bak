<?php declare(strict_types = 1);

namespace Vairogs\Tests\Assets\Functions;

use Vairogs\Functions\Text;

class ReflectionDataProvider
{
    private const HELPER_NAMESPACE = 'Vairogs\Functions';

    public static function dataProviderGetNamespace(): array
    {
        return [
            [Text::class, self::HELPER_NAMESPACE, ],
            ['Test', '\\', ],
        ];
    }
}
