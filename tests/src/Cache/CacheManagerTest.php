<?php declare(strict_types = 1);

namespace Vairogs\Tests\Source\Cache;

use Exception;
use Predis\Client;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Exception\CacheException;
use Vairogs\Cache\Adapter\Predis;
use Vairogs\Cache\CacheManager;
use Vairogs\Functions\Constants\Definition;
use Vairogs\Functions\Constants\Symbol;
use Vairogs\Functions\Identification;
use Vairogs\Tests\Assets\VairogsTestCase;

class CacheManagerTest extends VairogsTestCase
{
    protected const LIFETIME = 10;

    /**
     * @throws CacheException
     * @throws Exception
     *
     * @noinspection MissingService
     */
    public function testCacheManager(): void
    {
        $random = (new Identification())->getRandomString(chars: Symbol::EXTENDED);

        $cacheManager = new CacheManager(useFile: false);
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

        $manager = new CacheManager(Definition::DEFAULT_LIFETIME, false, new Predis(client: $predis, incDevReq: true));
        $random = (new Identification())->getRandomString();
        $manager->set(key: $random, value: __FUNCTION__, expiresAfter: 10);
        $this->assertEquals(expected: __FUNCTION__, actual: $manager->get(key: $random));
        $manager->delete(key: $random);
        $this->assertNull(actual: $manager->get(key: $random));
    }
}
