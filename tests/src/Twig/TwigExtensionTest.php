<?php declare(strict_types = 1);

namespace Vairogs\Tests\Source\Twig;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\ArrayLoader;
use Vairogs\Cache\CacheManager;
use Vairogs\Tests\Assets\Twig\TwigTestExtension;
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

        $twig = new Environment(loader: new ArrayLoader(templates: [$template]), options: ['debug' => true, 'cache' => false, 'autoescape' => false]);
        $twig->addExtension(extension: new TwigExtension(cacheManager: new CacheManager()));

        $result = $twig->load(name: 0)->render(context: []);

        if (false === $throws) {
            $this->assertEquals(expected: $message, actual: $result);
        }
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     *
     * @dataProvider \Vairogs\Tests\Assets\Twig\TwigExtensionDataProvider::dataProviderTwigTraitTemplates
     */
    public function testTwigTrait(string $template, string $value): void
    {
        $twig = new Environment(loader: new ArrayLoader(templates: [$template]), options: ['debug' => true, 'cache' => false, 'autoescape' => false]);
        $twig->addExtension(extension: new TwigTestExtension());

        $this->assertEquals(expected: $value, actual: $twig->load(0)->render(context: []));
    }
}
