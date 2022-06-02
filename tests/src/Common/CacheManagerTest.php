<?php declare(strict_types = 1);

namespace Vairogs\Tests\Source\Common;

use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Exception\CacheException;
use Vairogs\Common\CacheManager;
use Vairogs\Extra\Constants\Symbol;
use Vairogs\Tests\Assets\VairogsTestCase;
use Vairogs\Utils\Helper\Identification;

class CacheManagerTest extends VairogsTestCase
{
    /** @throws CacheException */
    public function testCacheManager(): void
    {
        $random = (new Identification())->getRandomString(chars: Symbol::EXTENDED);

        $cacheManager = (new CacheManager());
        $this->assertEquals(expected: null, actual: $cacheManager->get(key: __FUNCTION__));
        $this->assertEquals(expected: null, actual: $cacheManager->get(key: $random));
        $cacheManager->set(key: __FUNCTION__, value: __CLASS__, expiresAfter: 5);
        $cacheManager->set(key: $random, value: __CLASS__, expiresAfter: 5);
        $this->assertEquals(expected: __CLASS__, actual: $cacheManager->get(key: __FUNCTION__));
        $cacheManager->delete(key: __FUNCTION__);
        $cacheManager->delete(key: $random);
        $this->assertEquals(expected: null, actual: $cacheManager->get(key: __FUNCTION__));

        $this->assertEquals(expected: null, actual: (new CacheManager(useFile: false))->get(key: __FUNCTION__));
        $this->assertEquals(expected: null, actual: (new CacheManager(15, true, new CacheManager()))->get(key: __FUNCTION__));
        $this->assertEquals(expected: null, actual: (new CacheManager(15, true, new ArrayAdapter()))->get(key: __FUNCTION__));
    }
}
