<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\Tests;

use Error;
use Exception;
use LogicException;
use PHPUnit\Framework\TestCase;
use Vairogs\Component\Utils\Helper\Generator;
use Vairogs\Component\Utils\Helper\Text;
use function strlen;

class GeneratorTest extends TestCase
{
    public function testGetUniqueId(): void
    {
        $this->assertNotSame(Generator::getUniqueId(), Generator::getUniqueId());
        $this->assertSame(5, strlen(Generator::getUniqueId(5)));
        $this->assertNotSame(5, strlen(Generator::getUniqueId(6)));
        $this->expectException(Error::class);
        $this->expectExceptionMessage('Length must be greater than 0');
        /** @noinspection UnusedFunctionResultInspection */
        Generator::getUniqueId(0);
    }

    public function testGetRandomString(): void
    {
        $this->assertNotSame(Generator::getRandomString(), Generator::getRandomString());
        $this->assertSame(5, strlen(Generator::getUniqueId(5)));
        $this->assertNotSame(5, strlen(Generator::getUniqueId(6)));
    }

    /**
     * @throws Exception
     */
    public function testGenerate(): void
    {
        $this->assertNotSame((new Generator())->useLower()->generate(), (new Generator())->useLower()->generate());
        $this->assertTrue(Text::containsAny((new Generator())->useLower()->generate(), Generator::PASS_LOWERCASE));
        $this->assertFalse(Text::containsAny((new Generator())->useLower()->generate(), Generator::PASS_UPPERCASE));
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('At least one set must be used!');
        /** @noinspection UnusedFunctionResultInspection */
        (new Generator())->generate();
    }

    public function testUseLower(): void
    {
        $generator = new Generator();
        $generator->reset()->useLower();
        $this->assertTrue(isset($generator->getSets()[Generator::LOWER]));
        $this->assertFalse(isset($generator->getSets()[Generator::UPPER]));
    }

    public function testUseUpper(): void
    {
        $generator = new Generator();
        $generator->reset()->useUpper();
        $this->assertTrue(isset($generator->getSets()[Generator::UPPER]));
        $this->assertFalse(isset($generator->getSets()[Generator::DIGITS]));
    }

    public function testUseDigits(): void
    {
        $generator = new Generator();
        $generator->reset()->useDigits();
        $this->assertTrue(isset($generator->getSets()[Generator::DIGITS]));
        $this->assertFalse(isset($generator->getSets()[Generator::SYMBOLS]));
    }

    public function testUseSymbols(): void
    {
        $generator = new Generator();
        $generator->reset()->useSymbols();
        $this->assertTrue(isset($generator->getSets()[Generator::SYMBOLS]));
        $this->assertFalse(isset($generator->getSets()[Generator::LOWER]));
    }

    public function testReset(): void
    {
        $generator = new Generator();
        $generator->useLower();
        $this->assertTrue(isset($generator->getSets()[Generator::LOWER]));
        $generator->reset();
        $this->assertFalse(isset($generator->getSets()[Generator::LOWER]));
    }
}
