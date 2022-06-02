<?php declare(strict_types = 1);

namespace Vairogs\Tests\Source\Utils\Helper;

use ReflectionException;
use ReflectionMethod;
use Vairogs\Cache\Cache;
use Vairogs\Core\Vairogs;
use Vairogs\Tests\Assets\Utils\Helper\Model\TestAttribute;
use Vairogs\Tests\Assets\VairogsTestCase;
use Vairogs\Twig\Attribute\TwigFilter;
use Vairogs\Utils\Helper\Reflection;
use Vairogs\Utils\Helper\Text;

class ReflectionTest extends VairogsTestCase
{
    /** @throws ReflectionException */
    public function testAttributeExists(): void
    {
        $method = new ReflectionMethod(objectOrMethod: (new Reflection()), method: 'attributeExists');
        $this->assertTrue(condition: (new Reflection())->attributeExists(method: $method, filterClass: TwigFilter::class));
        $this->assertFalse(condition: (new Reflection())->attributeExists(method: $method, filterClass: Cache::class));
    }

    public function testGetFilteredMethods(): void
    {
        $this->assertArrayHasKey(key: 'sanitize', array: (new Reflection())->getFilteredMethods(class: Text::class, filterClass: TwigFilter::class));
        $this->assertEquals(expected: [], actual: (new Reflection())->getFilteredMethods(class: 'Test', filterClass: TwigFilter::class));
        $this->assertArrayHasKey(key: 'test', array: (new Reflection())->getFilteredMethods(class: TestAttribute::class, filterClass: TwigFilter::class));
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Utils\Helper\ReflectionDataProvider::dataProviderGetNamespace
     */
    public function testGetNamespace(string $class, string $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Reflection())->getNamespace(class: $class));
    }

    public function testShortName(): void
    {
        $this->assertEquals(expected: 'Vairogs', actual: (new Reflection())->getShortName(class: Vairogs::class));
        $this->assertEquals(expected: 'Test', actual: (new Reflection())->getShortName(class: 'Test'));
    }
}
