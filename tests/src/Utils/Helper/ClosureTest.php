<?php declare(strict_types = 1);

namespace Vairogs\Tests\Utils\Helper;

use Exception;
use InvalidArgumentException;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Vairogs\Assets\Utils\Doctrine\Traits\Entity;
use Vairogs\Assets\Utils\Helper\Model\Entity1;
use Vairogs\Assets\Utils\Helper\Model\Entity7;
use Vairogs\Assets\VairogsTestCase;
use Vairogs\Extra\Constants\Definition;
use Vairogs\Utils\Helper\Closure;
use function sprintf;

class ClosureTest extends VairogsTestCase
{
    public function testHijackCall(): void
    {
        (new Closure())->hijackCall(null, 'putenv', false, sprintf('%s=%s', __FUNCTION__, __CLASS__));
        $this->assertEquals(expected: __CLASS__, actual: (new Closure())->hijackCall(null, 'getenv', true, __FUNCTION__));
    }

    public function testHijackCallObject(): void
    {
        $entity = new Entity();
        (new Closure())->hijackCall($entity, 'setStatus', false, Definition::ENABLED);
        $this->assertEquals(expected: Definition::ENABLED, actual: (new Closure())->hijackCall(object: $entity, function: 'getStatus', return: true));
    }

    public function testHijackGet(): void
    {
        $entity1 = new Entity1();

        try {
            (new Closure())->hijackGet(object: $entity1, property: 'name', throwOnUnInitialized: true);
        } catch (Exception $exception) {
            $this->assertEquals(expected: InvalidPropertyPathException::class, actual: $exception::class);
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
