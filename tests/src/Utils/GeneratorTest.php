<?php declare(strict_types = 1);

namespace Vairogs\Tests\Source\Utils;

use Vairogs\Functions\Constants\Symbol;
use Vairogs\Functions\Text;
use Vairogs\Tests\Assets\VairogsTestCase;
use Vairogs\Utils\Generator;

use function function_exists;

class GeneratorTest extends VairogsTestCase
{
    public function testGenerate(): void
    {
        $generator = $reset = (new Generator())
            ->useLatvianLowerCase()
            ->useDigits()
            ->useLatvianUpperCase()
            ->useLower()
            ->useSymbols()
            ->useUpper()
            ->setDigits(digits: Symbol::DIGITS)
            ->setLatvianLowerCase(latvianLowerCase: Symbol::LV_LOWERCASE)
            ->setLatvianUpperCase(latvianUpperCase: Symbol::LV_UPPERCASE)
            ->setLowerCase(lowerCase: Symbol::EN_LOWERCASE)
            ->setUpperCase(upperCase: Symbol::EN_UPPERCASE)
            ->setSymbols(symbols: Symbol::SYMBOLS);

        $result = $generator->generate(length: 256);

        $this->assertTrue(condition: (new Text())->contains(haystack: $result, needle: Symbol::DIGITS));
        $this->assertTrue(condition: (new Text())->contains(haystack: $result, needle: Symbol::LV_LOWERCASE));
        $this->assertTrue(condition: (new Text())->contains(haystack: $result, needle: Symbol::LV_UPPERCASE));
        $this->assertTrue(condition: (new Text())->contains(haystack: $result, needle: Symbol::EN_LOWERCASE));
        $this->assertTrue(condition: (new Text())->contains(haystack: $result, needle: Symbol::EN_UPPERCASE));
        $this->assertTrue(condition: (new Text())->contains(haystack: $result, needle: Symbol::SYMBOLS));
        $this->assertNotEmpty(actual: $generator->getSets());

        if (function_exists(function: 'runkit7_function_remove')) {
            @runkit7_function_remove(function_name: 'random_int');
        }
        $result = $generator->generate(length: 256);

        $this->assertTrue(condition: (new Text())->contains(haystack: $result, needle: Symbol::DIGITS));
        $this->assertEmpty(actual: $reset->reset()->getSets());
        $this->assertEquals(expected: '', actual: $reset->generate());
    }
}
