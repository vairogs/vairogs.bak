<?php declare(strict_types = 1);

namespace Vairogs\Tests\Assets\Twig;

class TwigExtensionDataProvider
{
    public function dataProviderTwigTemplates(): array
    {
        return [
            ["{{ 'tests_tests'|vairogs_helper_text_limit_char(4) }}", true, 'Unknown "vairogs_helper_text_limit_char" filter', ],
            ["{{ 'tests_tests'|vairogs_helper_text_limit_chars(4) }}", false, 'test...', ],
        ];
    }
}
