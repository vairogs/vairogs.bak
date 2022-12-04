<?php declare(strict_types = 1);

namespace Vairogs\Tests\Source\Functions;

use DateTime;
use DateTimeInterface;
use Symfony\Component\PropertyAccess\Exception\AccessException;
use Vairogs\Core\Vairogs;
use Vairogs\Functions\Php;
use Vairogs\Functions\Text;
use Vairogs\Tests\Assets\Functions\Model\Entity1;
use Vairogs\Tests\Assets\Utils\Doctrine\Traits\Entity;
use Vairogs\Tests\Assets\VairogsTestCase;

class PhpTest extends VairogsTestCase
{
    /**
     * @dataProvider \Vairogs\Tests\Assets\Functions\PhpDataProvider::dataProviderBoolval
     */
    public function testBoolval(mixed $value, bool $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Php())->boolval(value: $value));
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Functions\PhpDataProvider::dataProviderGetterSetter
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
        $this->expectException(AccessException::class);
        (new Php())->getClassConstants(class: 'Test');
    }

    public function testCall(): void
    {
        $this->assertEquals(expected: 'sgoriav', actual: (new Php())->call(value: Vairogs::VAIROGS, function: 'strrev'));
    }

    public function testCallObject(): void
    {
        $this->assertEquals(expected: 'sgoriav', actual: (new Php())->callObject(value: Vairogs::VAIROGS, object: new Text(), function: 'reverseUTF8'));
    }

    public function testClassImplements(): void
    {
        $this->assertEquals(expected: true, actual: (new Php())->classImplements(class: DateTime::class, interface: DateTimeInterface::class));
    }
}
