<?php declare(strict_types = 1);

namespace Vairogs\Tests\Utils\Helper;

use Vairogs\Assets\VairogsTestCase;
use Vairogs\Utils\Helper\SortLatvian;

class SortLatvianTest extends VairogsTestCase
{
    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\SortLatvianDataProvider::dataProviderSortLatvian
     */
    public function testSortLatvian(array|object $unsorted, string|int $field, array|object $sorted): void
    {
        (new SortLatvian())->sortLatvian(names: $unsorted, field: $field);
        $this->assertEquals(expected: $sorted, actual: $unsorted);
    }
}
