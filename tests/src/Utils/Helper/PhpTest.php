<?php declare(strict_types = 1);

namespace Vairogs\Tests\Source\Utils\Helper;

use ReflectionException;
use ReflectionMethod;
use Symfony\Component\PropertyAccess\Exception\AccessException;
use Vairogs\Cache\Cache;
use Vairogs\Core\Vairogs;
use Vairogs\Tests\Assets\Utils\Doctrine\Traits\Entity;
use Vairogs\Tests\Assets\Utils\Helper\Model\Entity1;
use Vairogs\Tests\Assets\VairogsTestCase;
use Vairogs\Twig\Attribute\TwigFilter;
use Vairogs\Utils\Helper\Php;
use Vairogs\Utils\Helper\Text;

class PhpTest extends VairogsTestCase
{
    /**
     * @dataProvider \Vairogs\Tests\Assets\Utils\Helper\PhpDataProvider::dataProviderBoolval
     */
    public function testBoolval(mixed $value, bool $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Php())->boolval(value: $value));
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Utils\Helper\PhpDataProvider::dataProviderGetterSetter
     */
    public function testGetterSetter(string $variable, string $expGetter, string $expSetter): void
    {
        $this->assertEquals(expected: $expGetter, actual: (new Php())->getter(variable: $variable));
        $this->assertEquals(expected: $expSetter, actual: (new Php())->setter(variable: $variable));
    }

    /** @throws ReflectionException */
    public function testFilterExists(): void
    {
        $method = new ReflectionMethod(objectOrMethod: (new Php()), method: 'filterExists');
        $this->assertTrue(condition: (new Php())->filterExists(method: $method, filterClass: TwigFilter::class));
        $this->assertFalse(condition: (new Php())->filterExists(method: $method, filterClass: Cache::class));
    }

    public function testGetParameter(): void
    {
        $this->assertEquals(expected: 'value', actual: (new Php())->getParameter(variable: new Entity1(), key: 'value'));
    }

    public function testGetClassMethods(): void
    {
        $this->assertNotEquals(expected: (new Php())->getClassMethods(class: Entity1::class), actual: (new Php())->getClassMethods(class: Entity1::class, parent: Entity::class));
    }

    public function testGetClassConstants(): void
    {
        $this->expectException(AccessException::class);
        (new Php())->getClassConstants(class: 'Test');
    }

    public function testShortName(): void
    {
        $this->assertEquals(expected: 'Vairogs', actual: (new Php())->getShortName(class: Vairogs::class));
        $this->assertEquals(expected: 'Test', actual: (new Php())->getShortName(class: 'Test'));
    }

    public function testCall(): void
    {
        $this->assertEquals(expected: 'sgoriav', actual: (new Php())->call(value: Vairogs::VAIROGS, function: 'strrev'));
    }

    public function testCallObject(): void
    {
        $this->assertEquals(expected: 'sgoriav', actual: (new Php())->callObject(value: Vairogs::VAIROGS, object: new Text(), function: 'reverseUTF8'));
    }
}
