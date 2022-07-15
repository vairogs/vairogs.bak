<?php declare(strict_types = 1);

namespace Vairogs\Tests\Source\Common;

use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Exception\CacheException;
use Vairogs\Common\Adapter\PhpRedis;
use Vairogs\Common\Adapter\Predis;
use Vairogs\Common\CacheManager;
use Vairogs\Extra\Constants\Definition;
use Vairogs\Extra\Constants\Symbol;
use Vairogs\Tests\Assets\VairogsTestCase;
use Vairogs\Utils\Helper\Identification;

class CacheManagerTest extends VairogsTestCase
{
    protected const LIFETIME = 10;

    /** @throws CacheException */
    public function testCacheManager(): void
    {
        $random = (new Identification())->getRandomString(chars: Symbol::EXTENDED);

        $cacheManager = new CacheManager();
        $this->assertNull(actual: $cacheManager->get(key: __FUNCTION__));
        $this->assertNull(actual: $cacheManager->get(key: $random));
        $cacheManager->set(key: __FUNCTION__, value: __FUNCTION__, expiresAfter: self::LIFETIME);
        $cacheManager->set(key: $random, value: __FUNCTION__, expiresAfter: self::LIFETIME);
        $this->assertEquals(expected: __FUNCTION__, actual: $cacheManager->get(key: __FUNCTION__));
        $cacheManager->delete(key: __FUNCTION__);
        $cacheManager->delete(key: $random);
        $this->assertNull(actual: $cacheManager->get(key: __FUNCTION__));
        $cacheManager->set(key: __FUNCTION__, value: __FUNCTION__, expiresAfter: self::LIFETIME);
        $this->assertNull(actual: $cacheManager->get(key: __FUNCTION__, expiredTime: time() + self::LIFETIME));
        $cacheManager->delete(key: __FUNCTION__);

        $this->assertNull(actual: (new CacheManager(useFile: false))->get(key: __FUNCTION__));
        $this->assertNull(actual: (new CacheManager(15, true, new CacheManager()))->get(key: __FUNCTION__));
        $this->assertNull(actual: (new CacheManager(15, true, new ArrayAdapter()))->get(key: __FUNCTION__));

        $predis = new Predis(client: $this->container->get(id: 'snc_redis.predis'));
        $phpredis = new PhpRedis(client: $this->container->get(id: 'snc_redis.phpredis'));

        $manager = new CacheManager(Definition::DEFAULT_LIFETIME, false, $predis, $phpredis);
        $random = (new Identification())->getRandomString(chars: Symbol::BASIC);
        $manager->set(key: $random, value: __FUNCTION__, expiresAfter: 10);
        $this->assertEquals(expected: __FUNCTION__, actual: $manager->get(key: $random));
        $manager->delete(key: $random);
        $this->assertNull(actual: $manager->get(key: $random));
    }
}
