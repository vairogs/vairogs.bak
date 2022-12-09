<?php declare(strict_types = 1);

namespace Vairogs\Functions\Tests;

use Vairogs\Core\Tests\VairogsTestCase;
use Vairogs\Functions\SortLatvian;

class SortLatvianTest extends VairogsTestCase
{
    /**
     * @dataProvider \Vairogs\Functions\Tests\DataProvider\SortLatvianDataProvider::dataProviderSortLatvian
     */
    public function testSortLatvian(array|object $unsorted, string|int $field, array|object $sorted): void
    {
        (new SortLatvian())->sortLatvian(names: $unsorted, field: $field);
        $this->assertEquals(expected: $sorted, actual: $unsorted);
    }
}
