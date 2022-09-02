<?php declare(strict_types = 1);

namespace Vairogs\Tests\Source\Cache;

use Exception;
use Predis\Client;
use Redis;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Exception\CacheException;
use Vairogs\Cache\Adapter\PhpRedis;
use Vairogs\Cache\Adapter\Predis;
use Vairogs\Cache\CacheManager;
use Vairogs\Extra\Constants\Definition;
use Vairogs\Extra\Constants\Symbol;
use Vairogs\Tests\Assets\VairogsTestCase;
use Vairogs\Utils\Helper\Identification;

class CacheManagerTest extends VairogsTestCase
{
    protected const LIFETIME = 10;

    /**
     * @throws CacheException
     * @throws Exception
     */
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

        /**
         * @var Client $predis
         */
        $predis = $this->container->get(id: 'snc_redis.predis');

        /**
         * @var Redis $phpredis
         */
        $phpredis = $this->container->get(id: 'snc_redis.phpredis');

        $manager = new CacheManager(Definition::DEFAULT_LIFETIME, false, new Predis(client: $predis), new PhpRedis(client: $phpredis));
        $random = (new Identification())->getRandomString();
        $manager->set(key: $random, value: __FUNCTION__, expiresAfter: 10);
        $this->assertEquals(expected: __FUNCTION__, actual: $manager->get(key: $random));
        $manager->delete(key: $random);
        $this->assertNull(actual: $manager->get(key: $random));
    }
}
