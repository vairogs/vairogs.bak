<?php declare(strict_types = 1);

namespace Vairogs\Tests\Source\Functions;

use ReflectionException;
use ReflectionMethod;
use Vairogs\Cache\Cache;
use Vairogs\Core\Vairogs;
use Vairogs\Functions\Reflection;
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
