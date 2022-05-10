<?php declare(strict_types = 1);

namespace Vairogs\Tests\Common;

use Symfony\Component\Cache\Adapter\ChainAdapter;
use Symfony\Component\Cache\Adapter\NullAdapter;
use Vairogs\Assets\VairogsTestCase;
use Vairogs\Common\Cache;
use Vairogs\Common\Common;

class CommonTest extends VairogsTestCase
{
    use Cache;

    /**
     * @noinspection PhpUnhandledExceptionInspection
     * @noinspection UnnecessaryAssertionInspection
     */
    public function testGetChainAdapter(): void
    {
        $adapter = (new Common())->getChainAdapter(class: __CLASS__);
        $this->assertInstanceOf(expected: ChainAdapter::class, actual: $adapter);
        $this->assertEquals(expected: null, actual: $this->getCache(adapter: $adapter, key: __FUNCTION__));
        $this->setCache(adapter: $adapter, key: __FUNCTION__, value: __CLASS__, expiresAfter: 10);
        $this->assertEquals(expected: __CLASS__, actual: $this->getCache(adapter: $adapter, key: __FUNCTION__));
        $adapter->delete(key: __FUNCTION__);
        $this->assertEquals(expected: null, actual: $this->getCache(adapter: $adapter, key: __FUNCTION__));
        $this->assertInstanceOf(expected: ChainAdapter::class, actual: (new Common())->getChainAdapter(__CLASS__, 10, new NullAdapter()));
    }
}
