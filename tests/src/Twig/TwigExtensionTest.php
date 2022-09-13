<?php declare(strict_types = 1);

namespace Vairogs\Tests\Source\Twig;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\ArrayLoader;
use Vairogs\Cache\CacheManager;
use Vairogs\Tests\Assets\VairogsTestCase;
use Vairogs\Twig\TwigExtension;

class TwigExtensionTest extends VairogsTestCase
{
    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     *
     * @dataProvider \Vairogs\Tests\Assets\Twig\TwigExtensionDataProvider::dataProviderTwigTemplates
     */
    public function testTwigExtension(string $template, bool $throws, ?string $message = null): void
    {
        if (true === $throws) {
            $this->expectExceptionMessage(message: $message);
        }

        $result = $this->getEnvironment(templates: [$template], )->load(name: 0)->render(context: []);

        if (false === $throws) {
            $this->assertEquals(expected: $message, actual: $result);
        }
    }

    protected function getEnvironment($options = [], $templates = []): Environment
    {
        $twig = new Environment(loader: new ArrayLoader(templates: $templates), options: array_merge(['debug' => true, 'cache' => false, 'autoescape' => false], $options));
        $twig->addExtension(extension: new TwigExtension(cacheManager: new CacheManager()));

        return $twig;
    }
}
