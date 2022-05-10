<?php declare(strict_types = 1);

namespace Vairogs\Tests\Utils;

use LogicException;
use Vairogs\Assets\VairogsTestCase;
use Vairogs\Extra\Constants\Symbol;
use Vairogs\Utils\Generator;
use Vairogs\Utils\Helper\Text;
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
        $this->expectException(exception: LogicException::class);
        $reset->generate();
    }
}
