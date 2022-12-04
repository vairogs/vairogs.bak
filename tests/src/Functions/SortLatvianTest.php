<?php declare(strict_types = 1);

namespace Vairogs\Tests\Source\Functions;

use Vairogs\Functions\SortLatvian;
use Vairogs\Tests\Assets\VairogsTestCase;

class SortLatvianTest extends VairogsTestCase
{
    /**
     * @dataProvider \Vairogs\Tests\Assets\Functions\SortLatvianDataProvider::dataProviderSortLatvian
     */
    public function testSortLatvian(array|object $unsorted, string|int $field, array|object $sorted): void
    {
        (new SortLatvian())->sortLatvian(names: $unsorted, field: $field);
        $this->assertEquals(expected: $sorted, actual: $unsorted);
    }
}
