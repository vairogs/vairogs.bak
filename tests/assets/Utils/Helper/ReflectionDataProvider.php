<?php declare(strict_types = 1);

namespace Vairogs\Tests\Assets\Utils\Helper;

use Vairogs\Twig\TwigExtension;
use Vairogs\Utils\Helper\Text;

class ReflectionDataProvider
{
    public function dataProviderGetNamespace(): array
    {
        return [
            [Text::class, TwigExtension::HELPER_NAMESPACE, ],
            ['Test', '\\', ],
        ];
    }
}
