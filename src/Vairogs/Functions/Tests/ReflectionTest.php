<?php declare(strict_types = 1);

namespace Vairogs\Functions\Tests;

use ReflectionException;
use ReflectionMethod;
use Symfony\Component\Routing\Route;
use Vairogs\Core\Tests\VairogsTestCase;
use Vairogs\Functions\Reflection;
use Vairogs\Functions\Tests\Assets\Twig\TestFunctions;
use Vairogs\Functions\Text;

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

    public function testGetFilteredMethods(): void
    {
        $this->assertCount(expectedCount: 0, haystack: (new Reflection())->getFilteredMethods(class: 'Nothing'));
        $this->assertCount(expectedCount: 1, haystack: (new Reflection())->getFilteredMethods(class: TestFunctions::class));
        $this->assertCount(expectedCount: 15, haystack: (new Reflection())->getFilteredMethods(class: Text::class));
    }
}
