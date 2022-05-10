<?php declare(strict_types = 1);

namespace Vairogs\Tests\Utils\Helper;

use Vairogs\Assets\VairogsTestCase;
use Vairogs\Utils\Helper\Text;

class TextTest extends VairogsTestCase
{
    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\TextDataProvider::dataProviderOneStripSpace
     */
    public function testOneStripSpace(string $text, string $one, string $none): void
    {
        $this->assertEquals(expected: $one, actual: (new Text())->oneSpace(text: $text));
        $this->assertEquals(expected: $none, actual: (new Text())->stripSpace(text: $text));
    }

    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\TextDataProvider::dataProviderLimit
     */
    public function testLimit(string $text, int $limit, int $words, string $append, string $strict, string $safe, string $word): void
    {
        $this->assertEquals(expected: $strict, actual: (new Text())->limitChars(text: $text, length: $limit, append: $append));
        $this->assertEquals(expected: $safe, actual: (new Text())->truncateSafe(text: $text, length: $limit, append: $append));
        $this->assertEquals(expected: $word, actual: (new Text())->limitWords(text: $text, limit: $words, append: $append));
    }
}
