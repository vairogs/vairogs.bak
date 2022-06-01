<?php declare(strict_types = 1);

namespace Vairogs\Tests\Source\Utils\Helper;

use InvalidArgumentException;
use Vairogs\Tests\Assets\VairogsTestCase;
use Vairogs\Utils\Helper\Pagination;

class PaginationTest extends VairogsTestCase
{
    /**
     * @dataProvider \Vairogs\Tests\Assets\Utils\Helper\PaginationDataProvider::dataProvider
     */
    public function testPagination(int $visible, int $total, int $current, int $ommit, array $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Pagination())->pagination(visible: $visible, total: $total, current: $current, ommit: $ommit));
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Utils\Helper\PaginationDataProvider::dataProviderException
     */
    public function testPaginationException(int $visible, int $total, int $current, int $ommit, array $expected): void
    {
        try {
            $this->assertEquals(expected: $expected, actual: (new Pagination())->pagination(visible: $visible, total: $total, current: $current, ommit: $ommit));
        } catch (InvalidArgumentException $exception) {
            $this->assertInstanceOf(expected: InvalidArgumentException::class, actual: $exception);
        }
    }
}
