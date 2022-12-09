<?php declare(strict_types = 1);

namespace Vairogs\Functions\Tests;

use Vairogs\Core\Tests\VairogsTestCase;
use Vairogs\Functions\Text;

use function htmlentities;

class TextTest extends VairogsTestCase
{
    /**
     * @dataProvider \Vairogs\Functions\Tests\DataProvider\TextDataProvider::dataProviderOneStripSpace
     */
    public function testOneStripSpace(string $text, string $one, string $none): void
    {
        $this->assertEquals(expected: $one, actual: (new Text())->oneSpace(text: $text));
        $this->assertEquals(expected: $none, actual: (new Text())->stripSpace(text: $text));
    }

    /**
     * @dataProvider \Vairogs\Functions\Tests\DataProvider\TextDataProvider::dataProviderLimit
     */
    public function testLimit(string $text, int $limit, int $words, string $append, string $strict, string $safe, string $word): void
    {
        $this->assertEquals(expected: $strict, actual: (new Text())->limitChars(text: $text, length: $limit, append: $append));
        $this->assertEquals(expected: $safe, actual: (new Text())->truncateSafe(text: $text, length: $limit, append: $append));
        $this->assertEquals(expected: $word, actual: (new Text())->limitWords(text: $text, limit: $words, append: $append));
    }

    /**
     * @dataProvider \Vairogs\Functions\Tests\DataProvider\TextDataProvider::dataProviderGetLastPart
     */
    public function testGetLastPart(string $string, string $delimiter, string $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Text())->getLastPart(text: $string, delimiter: $delimiter));
    }

    /**
     * @dataProvider \Vairogs\Functions\Tests\DataProvider\TextDataProvider::dataProviderGetNormalizedValue
     */
    public function testGetNormalizedValue(string $value, string $delimiter, int|float|string $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Text())->getNormalizedValue(value: $value, delimiter: $delimiter));
    }

    /**
     * @dataProvider \Vairogs\Functions\Tests\DataProvider\TextDataProvider::dataProviderHtmlEntityDecode
     */
    public function testHtmlEntityDecode(string $html): void
    {
        $this->assertEquals(expected: $html, actual: (new Text())->htmlEntityDecode(text: htmlentities(string: $html)));
    }

    /**
     * @dataProvider \Vairogs\Functions\Tests\DataProvider\TextDataProvider::dataProvideReverseUTF8
     */
    public function testReverseUTF8(string $text, string $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Text())->reverseUTF8(text: $text));
    }

    /**
     * @dataProvider \Vairogs\Functions\Tests\DataProvider\TextDataProvider::dataProviderHtmlEntityDecode
     */
    public function testCleanText(string $html): void
    {
        $this->assertEquals(expected: $html, actual: (new Text())->cleanText(text: htmlentities(string: $html)));
    }

    /**
     * @dataProvider \Vairogs\Functions\Tests\DataProvider\TextDataProvider::dataProviderContainsAny
     */
    public function testContainsAny(string $haystack, array $needles, bool $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Text())->containsAny(haystack: $haystack, needles: $needles));
    }

    /**
     * @dataProvider \Vairogs\Functions\Tests\DataProvider\TextDataProvider::dataProviderSanitize
     */
    public function testSanitize(string $input, string $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Text())->sanitize(text: $input));
    }

    /**
     * @dataProvider \Vairogs\Functions\Tests\DataProvider\TextDataProvider::dataProviderlongestSubstrLength
     */
    public function testLongestSubstrLength(string $string, int $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Text())->longestSubstrLength(string: $string));
    }
}
