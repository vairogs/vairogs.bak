<?php declare(strict_types = 1);

namespace Vairogs\Functions\Tests\DataProvider;

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

    public static function dataProviderTwigTemplates(): array
    {
        return [
            ["{{ 'tests_tests'|vairogs_utils_helper_text_limit_char(4) }}", true, 'Unknown "vairogs_utils_helper_text_limit_char" filter', ],
            ["{{ 'tests_tests'|vairogs_functions_text_limit_chars(4) }}", false, 'test...', ],
        ];
    }
}
