<?php declare(strict_types = 1);

namespace Vairogs\Tests\Source\Functions;

use InvalidArgumentException;
use Vairogs\Functions\Pagination;
use Vairogs\Tests\Assets\VairogsTestCase;

class PaginationTest extends VairogsTestCase
{
    /**
     * @dataProvider \Vairogs\Tests\Assets\Functions\PaginationDataProvider::dataProvider
     */
    public function testPagination(int $visible, int $total, int $current, int $indicator, array $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Pagination())->paginate(visible: $visible, total: $total, current: $current, indicator: $indicator));
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Functions\PaginationDataProvider::dataProviderException
     */
    public function testPaginationException(int $visible, int $total, int $current, int $indicator, array $expected): void
    {
        try {
            $this->assertEquals(expected: $expected, actual: (new Pagination())->paginate(visible: $visible, total: $total, current: $current, indicator: $indicator));
        } catch (InvalidArgumentException $exception) {
            $this->assertInstanceOf(expected: InvalidArgumentException::class, actual: $exception);
        }
    }
}
