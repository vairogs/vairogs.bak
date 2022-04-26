<?php declare(strict_types = 1);

namespace Vairogs\Tests\Utils\Helper;

use PHPUnit\Framework\TestCase;
use Vairogs\Utils\Helper\Text;

class TextTest extends TestCase
{
    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\TextDataProvider::dataProviderOneStripSpace
     */
    public function testOneStripSpace(string $text, string $one, string $none): void
    {
        $this->assertEquals(expected: $one, actual: Text::oneSpace(text: $text));
        $this->assertEquals(expected: $none, actual: Text::stripSpace(text: $text));
    }

    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\TextDataProvider::dataProviderLimit
     */
    public function testLimit(string $text, int $limit, int $words, string $append, string $strict, string $safe, string $word): void
    {
        $this->assertEquals(expected: $strict, actual: Text::limitChars(text: $text, length: $limit, append: $append));
        $this->assertEquals(expected: $safe, actual: Text::truncateSafe(text: $text, length: $limit, append: $append));
        $this->assertEquals(expected: $word, actual: Text::limitWords(text: $text, limit: $words, append: $append));
    }
}
