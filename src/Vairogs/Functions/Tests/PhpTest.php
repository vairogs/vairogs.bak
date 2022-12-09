<?php declare(strict_types = 1);

namespace Vairogs\Functions\Tests;

use BadFunctionCallException;
use DateTime;
use DateTimeInterface;
use Vairogs\Core\Tests\VairogsTestCase;
use Vairogs\Functions\Php;
use Vairogs\Functions\Tests\Entity\Entity;
use Vairogs\Functions\Tests\Model\Entity1;
use Vairogs\Functions\Text;

class PhpTest extends VairogsTestCase
{
    /**
     * @dataProvider \Vairogs\Functions\Tests\DataProvider\PhpDataProvider::dataProviderBoolval
     */
    public function testBoolval(mixed $value, bool $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Php())->boolval(value: $value));
    }

    /**
     * @dataProvider \Vairogs\Functions\Tests\DataProvider\PhpDataProvider::dataProviderGetterSetter
     */
    public function testGetterSetter(string $variable, string $expGetter, string $expSetter): void
    {
        $this->assertEquals(expected: $expGetter, actual: (new Php())->getter(variable: $variable));
        $this->assertEquals(expected: $expSetter, actual: (new Php())->setter(variable: $variable));
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
        $this->expectException(BadFunctionCallException::class);
        (new Php())->getClassConstants(class: 'Test');
    }

    public function testCall(): void
    {
        $this->assertEquals(expected: 'sgoriav', actual: (new Php())->call(value: 'vairogs', function: 'strrev'));
    }

    public function testCallObject(): void
    {
        $this->assertEquals(expected: 'sgoriav', actual: (new Php())->callObject(value: 'vairogs', object: new Text(), function: 'reverseUTF8'));
    }

    public function testClassImplements(): void
    {
        $this->assertEquals(expected: true, actual: (new Php())->classImplements(class: DateTime::class, interface: DateTimeInterface::class));
    }
}
