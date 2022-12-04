<?php declare(strict_types = 1);

namespace Vairogs\Tests\Source\Functions;

use ReflectionException;
use ReflectionMethod;
use Vairogs\Cache\Cache;
use Vairogs\Core\Attribute\CoreFilter;
use Vairogs\Core\Vairogs;
use Vairogs\Functions\Reflection;
use Vairogs\Functions\Text;
use Vairogs\Tests\Assets\Functions\Model\TestAttribute;
use Vairogs\Tests\Assets\VairogsTestCase;

class ReflectionTest extends VairogsTestCase
{
    /**
     * @throws ReflectionException
     */
    public function testAttributeExists(): void
    {
        $reflectionMethod = new ReflectionMethod(objectOrMethod: (new Reflection()), method: 'attributeExists');
        $this->assertFalse(condition: (new Reflection())->attributeExists(reflectionMethod: $reflectionMethod, filterClass: Cache::class));
    }

    public function testGetFilteredMethods(): void
    {
        $this->markTestSkipped();

        $this->assertArrayHasKey(key: 'sanitize', array: (new Reflection())->getFilteredMethods(class: Text::class, filterClass: CoreFilter::class));
        $this->assertEquals(expected: [], actual: (new Reflection())->getFilteredMethods(class: 'Test', filterClass: CoreFilter::class));
        $this->assertArrayHasKey(key: 'test', array: (new Reflection())->getFilteredMethods(class: TestAttribute::class, filterClass: CoreFilter::class));
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Functions\ReflectionDataProvider::dataProviderGetNamespace
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
