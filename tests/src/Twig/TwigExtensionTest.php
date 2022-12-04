<?php declare(strict_types = 1);

namespace Vairogs\Tests\Source\Twig;

use Exception;
use Predis\Client;
use Symfony\Component\Cache\Exception\CacheException;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\ArrayLoader;
use Vairogs\Cache\Adapter\Predis;
use Vairogs\Cache\CacheManager;
use Vairogs\Tests\Assets\Twig\TwigTestExtension;
use Vairogs\Tests\Assets\VairogsTestCase;
use Vairogs\Twig\TwigExtension;

class TwigExtensionTest extends VairogsTestCase
{
    /**
     * @dataProvider \Vairogs\Tests\Assets\Twig\TwigExtensionDataProvider::dataProviderTwigTemplates
     *
     * @throws CacheException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     *
     * @noinspection MissingService
     */
    public function testTwigExtension(string $template, bool $throws, ?string $message = null): void
    {
        $this->markTestSkipped();

        if (true === $throws) {
            $this->expectExceptionMessage(message: $message);
        }

        /**
         * @var Client $predis
         */
        $predis = $this->container->get(id: 'snc_redis.predis');

        $manager = new CacheManager(60, false, new Predis(client: $predis, incDevReq: true));
        $extension = new TwigExtension(cacheManager: $manager);
        $manager->delete(key: $extension->getKey(type: 'getFilters'));

        $twig = new Environment(loader: new ArrayLoader(templates: [$template]), options: ['debug' => true, 'cache' => false, 'autoescape' => false]);
        $twig->addExtension(extension: $extension);

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
        $this->markTestSkipped();

        $twig = new Environment(loader: new ArrayLoader(templates: [$template]), options: ['debug' => true, 'cache' => false, 'autoescape' => false]);
        $twig->addExtension(extension: new TwigTestExtension());

        $this->assertEquals(expected: $value, actual: $twig->load(0)->render(context: []));
    }
}
