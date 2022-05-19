<?php declare(strict_types = 1);

namespace Vairogs\Tests\Common;

use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Exception\CacheException;
use Vairogs\Assets\VairogsTestCase;
use Vairogs\Common\CacheManager;
use Vairogs\Extra\Constants\Symbol;
use Vairogs\Utils\Helper\Identification;

class CacheManagerTest extends VairogsTestCase
{
    /** @throws CacheException */
    public function testCacheManager(): void
    {
        $random = (new Identification())->getRandomString(chars: Symbol::EXTENDED);

        $manager = (new CacheManager());
        $this->assertEquals(expected: null, actual: $manager->get(key: __FUNCTION__));
        $this->assertEquals(expected: null, actual: $manager->get(key: $random));
        $manager->set(key: __FUNCTION__, value: __CLASS__, expiresAfter: 15);
        $manager->set(key: $random, value: __CLASS__, expiresAfter: 15);
        $this->assertEquals(expected: __CLASS__, actual: $manager->get(key: __FUNCTION__));
        $manager->delete(key: __FUNCTION__);
        $manager->delete(key: $random);
        $this->assertEquals(expected: null, actual: $manager->get(key: __FUNCTION__));

        $this->assertEquals(expected: null, actual: (new CacheManager(useFile: false))->get(key: __FUNCTION__));
        $this->assertEquals(expected: null, actual: (new CacheManager(15, true, new CacheManager()))->get(key: __FUNCTION__));
        $this->assertEquals(expected: null, actual: (new CacheManager(15, true, new ArrayAdapter()))->get(key: __FUNCTION__));
    }
}
