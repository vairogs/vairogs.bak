<?php declare(strict_types = 1);

namespace Vairogs\Tests\Common;

use BadMethodCallException;
use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Vairogs\Common\Pool;

class PoolTest extends TestCase
{
    public function testCreatePool(): void
    {
        try {
            Pool::createPool(class: __CLASS__);
        } catch (Exception $exception) {
            $this->assertEquals(expected: BadMethodCallException::class, actual: $exception::class);
        }

        try {
            Pool::createPool(class: __CLASS__, adapters: ['vairogs']);
        } catch (Exception $exception) {
            $this->assertEquals(expected: InvalidArgumentException::class, actual: $exception::class);
        }
    }
}
