<?php declare(strict_types = 1);

namespace Vairogs\Tests\Source\Utils\Helper;

use Doctrine\Common\Collections\Collection;
use InvalidArgumentException;
use Vairogs\Extra\Constants\Enum\Order as Enum;
use Vairogs\Tests\Assets\Doctrine\Traits\Entity;
use Vairogs\Tests\Assets\VairogsTestCase;
use Vairogs\Utils\Helper\Order;

class OrderTest extends VairogsTestCase
{
    /**
     * @dataProvider \Vairogs\Tests\Assets\Utils\Helper\OrderDataProvider::dataProviderSort
     */
    public function testSort(array|object $data, string $parameter, Enum $order, array $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Order())->sort(data: $data, parameter: $parameter, order: $order));
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Utils\Helper\OrderDataProvider::dataProviderSortException
     */
    public function testSortException(iterable|Collection $data, string $parameter, Enum $order): void
    {
        $this->expectException(exception: InvalidArgumentException::class);
        (new Order())->sort(data: $data, parameter: $parameter, order: $order);
    }

    public function testIsSortable(): void
    {
        $entity = (new Entity())
            ->setId(id: 1);

        $this->assertFalse(condition: (new Order())->isSortable(item: 1, field: 'value'));
        $this->assertFalse(condition: (new Order())->isSortable(item: $entity, field: 'test'));
        $this->assertTrue(condition: (new Order())->isSortable(item: $entity, field: 'id'));
    }
}
