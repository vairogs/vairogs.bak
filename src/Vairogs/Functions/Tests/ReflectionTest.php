<?php declare(strict_types = 1);

namespace Vairogs\Functions\Tests;

use Exception;
use ReflectionException;
use ReflectionMethod;
use Symfony\Component\Cache\Exception\CacheException;
use Symfony\Component\Routing\Route;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\ArrayLoader;
use Vairogs\Core\Tests\VairogsTestCase;
use Vairogs\Functions\Reflection;
use Vairogs\Functions\Tests\Service\NullCacheManager;
use Vairogs\Functions\Tests\Twig\TestFunctions;
use Vairogs\Functions\Text;
use Vairogs\Twig\TwigExtension;

class ReflectionTest extends VairogsTestCase
{
    /**
     * @throws ReflectionException
     */
    public function testAttributeExists(): void
    {
        $reflectionMethod = new ReflectionMethod(objectOrMethod: (new Reflection()), method: 'attributeExists');
        $this->assertFalse(condition: (new Reflection())->attributeExists(reflectionMethod: $reflectionMethod, filterClass: Route::class));
    }

    /**
     * @dataProvider \Vairogs\Functions\Tests\DataProvider\ReflectionDataProvider::dataProviderGetNamespace
     */
    public function testGetNamespace(string $class, string $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Reflection())->getNamespace(class: $class));
    }

    public function testShortName(): void
    {
        $this->assertEquals(expected: 'ReflectionTest', actual: (new Reflection())->getShortName(class: __CLASS__));
        $this->assertEquals(expected: 'Test', actual: (new Reflection())->getShortName(class: 'Test'));
    }

    /**
     * @dataProvider \Vairogs\Functions\Tests\DataProvider\ReflectionDataProvider::dataProviderTwigTemplates
     *
     * @throws CacheException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     */
    public function testTwigExtension(string $template, bool $throws, ?string $message = null): void
    {
        if (true === $throws) {
            $this->expectExceptionMessage(message: $message);
        }

        $manager = new NullCacheManager();
        $extension = new TwigExtension(cacheManager: $manager, enabled: true, classes: [Text::class, TestFunctions::class, 'Test', ]);
        $manager->delete(key: $extension->getKey(type: 'getFilters'));

        $twig = new Environment(loader: new ArrayLoader(templates: [$template]), options: ['debug' => true, 'cache' => false, 'autoescape' => false]);
        $twig->addExtension(extension: $extension);

        $result = $twig->load(name: 0)->render(context: []);

        if (false === $throws) {
            $this->assertEquals(expected: $message, actual: $result);
        }
    }
}
