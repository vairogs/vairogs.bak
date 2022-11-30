<?php declare(strict_types = 1);

namespace Vairogs\Tests\Assets\Utils\Helper;

use Vairogs\Utils\Helper\Text;

class ReflectionDataProvider
{
    private const HELPER_NAMESPACE = 'Vairogs\Utils\Helper';

    public static function dataProviderGetNamespace(): array
    {
        return [
            [Text::class, self::HELPER_NAMESPACE, ],
            ['Test', '\\', ],
        ];
    }
}
