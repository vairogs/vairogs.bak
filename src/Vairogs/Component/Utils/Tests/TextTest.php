<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\Tests;

use PHPUnit\Framework\TestCase;
use Vairogs\Component\Utils\Helper\Text;

class TextTest extends TestCase
{
    public function testFromCamelCase(): void
    {
        $this->assertSame('one_two_three', Text::fromCamelCase('oneTwoThree'));
        $this->assertSame('one_two_three', Text::fromCamelCase('OneTwoThree'));
        $this->assertSame('one', Text::fromCamelCase('one'));
        $this->assertSame('one', Text::fromCamelCase('One'));
    }

    public function testToSnakeCase(): void
    {
        $this->assertEquals('onetwothree', Text::toSnakeCase('oneTwoThree'));
    }
}
