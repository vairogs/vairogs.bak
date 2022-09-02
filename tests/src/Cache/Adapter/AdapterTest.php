<?php declare(strict_types = 1);

namespace Vairogs\Tests\Source\Cache\Adapter;

use Symfony\Component\Cache\Adapter\DoctrineDbalAdapter;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Vairogs\Cache\Adapter\Orm;
use Vairogs\Cache\Adapter\PhpRedis;
use Vairogs\Cache\Adapter\Predis;
use Vairogs\Tests\Assets\VairogsTestCase;

use function sprintf;

class AdapterTest extends VairogsTestCase
{
    public function testRedisAdapters(): void
    {
        $predis = new Predis(client: $this->container->get(id: 'snc_redis.predis'));
        $phpredis = new PhpRedis(client: $this->container->get(id: 'snc_redis.phpredis'));

        $this->assertInstanceOf(expected: Predis::class, actual: $predis);
        $this->assertInstanceOf(expected: PhpRedis::class, actual: $phpredis);

        $this->assertInstanceOf(expected: RedisAdapter::class, actual: $predis->getAdapter());
        $this->assertInstanceOf(expected: RedisAdapter::class, actual: $phpredis->getAdapter());
    }

    /**
     * @noinspection PhpUnhandledExceptionInspection
     */
    public function testOrmAdapter(): void
    {
        $orm = new Orm(entityManager: $em = $this->container->get(id: 'doctrine.orm.default_entity_manager'), namespace: __FUNCTION__);
        $this->assertInstanceOf(expected: Orm::class, actual: $orm);
        $this->assertInstanceOf(expected: DoctrineDbalAdapter::class, actual: $orm->getAdapter());
        $em->getConnection()->createSchemaManager()->dropTable(name: sprintf('%s_items', __FUNCTION__));
    }
}
