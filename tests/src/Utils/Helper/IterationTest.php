<?php declare(strict_types = 1);

namespace Vairogs\Tests\Utils\Helper;

use PHPUnit\Framework\TestCase;
use Vairogs\Utils\Helper\Iteration;

class IterationTest extends TestCase
{
    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\IterationDataProvider::dataProviderIsEmpty
     */
    public function testIsEmpty(mixed $value, bool $expected): void
    {
        $this->assertEquals(expected: $expected, actual: Iteration::isEmpty(variable: $value));
    }

    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\IterationDataProvider::dataProviderMakeMultiDimensional
     */
    public function testMakeMultiDimensional(array $input, array $expected): void
    {
        $this->assertEquals(expected: $expected, actual: Iteration::makeMultiDimensional(array: $input));
    }

    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\IterationDataProvider::dataProviderUniqueMap
     */
    public function testUniqueMap(array $input, array $expected): void
    {
        Iteration::uniqueMap(array: $input);
        $this->assertEquals(expected: $expected, actual: $input);
    }

    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\IterationDataProvider::dataProviderUnique
     */
    public function testUnique(array $input, array $expected, bool $keep): void
    {
        $this->assertEquals(expected: $expected, actual: Iteration::unique(input: $input, keepKeys: $keep));
    }
}
