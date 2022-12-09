<?php declare(strict_types = 1);

namespace Vairogs\Functions\Tests;

use Exception;
use InvalidArgumentException;
use Vairogs\Core\Tests\VairogsTestCase;
use Vairogs\Functions\Closure;
use Vairogs\Functions\Constants\Status;
use Vairogs\Functions\Tests\Assets\Model\Entity;
use Vairogs\Functions\Tests\Assets\Model\Entity1;
use Vairogs\Functions\Tests\Assets\Model\Entity7;

use function sprintf;

class ClosureTest extends VairogsTestCase
{
    public function testHijackCall(): void
    {
        (new Closure())->hijackVoid('putenv', sprintf('%s=%s', __FUNCTION__, __CLASS__));
        $this->assertEquals(expected: __CLASS__, actual: (new Closure())->hijackReturn('getenv', __FUNCTION__));
    }

    public function testHijackCallObject(): void
    {
        $entity = new Entity();
        (new Closure())->hijackVoidObject($entity, 'setStatus', Status::ONE);
        $this->assertEquals(expected: Status::ONE, actual: (new Closure())->hijackReturnObject(object: $entity, function: 'getStatus'));
    }

    public function testHijackGet(): void
    {
        $entity1 = new Entity1();

        try {
            (new Closure())->hijackGet(object: $entity1, property: 'name', throwOnUnInitialized: true);
        } catch (Exception $exception) {
            $this->assertEquals(expected: InvalidArgumentException::class, actual: $exception::class);
        }

        $this->assertNull(actual: (new Closure())->hijackGet(object: $entity1, property: 'name', throwOnUnInitialized: false));

        try {
            (new Closure())->hijackGet(object: $entity1, property: 'name2', throwOnUnInitialized: true);
        } catch (Exception $exception) {
            $this->assertEquals(expected: InvalidArgumentException::class, actual: $exception::class);
        }

        try {
            (new Closure())->hijackGetStatic(object: $entity1, property: 'name');
        } catch (Exception $exception) {
            $this->assertEquals(expected: InvalidArgumentException::class, actual: $exception::class);
        }

        try {
            (new Closure())->hijackGetStatic(object: $entity1, property: 'name2');
        } catch (Exception $exception) {
            $this->assertEquals(expected: InvalidArgumentException::class, actual: $exception::class);
        }
    }

    public function testHijackSet(): void
    {
        $entity7 = new Entity7();

        (new Closure())->hijackSet(object: $entity7, property: 'name', value: __FUNCTION__);
        $this->assertEquals(expected: __FUNCTION__, actual: $entity7->getName());

        (new Closure())->hijackSet(object: $entity7, property: 'value', value: __FUNCTION__);
        $this->assertEquals(expected: __FUNCTION__, actual: $entity7::getStaticValue());

        try {
            (new Closure())->hijackSet(object: $entity7, property: 'name2', value: __FUNCTION__);
        } catch (Exception $exception) {
            $this->assertEquals(expected: InvalidArgumentException::class, actual: $exception::class);
        }

        try {
            (new Closure())->hijackSetStatic(object: $entity7, property: 'name2', value: __FUNCTION__);
        } catch (Exception $exception) {
            $this->assertEquals(expected: InvalidArgumentException::class, actual: $exception::class);
        }
    }
}
