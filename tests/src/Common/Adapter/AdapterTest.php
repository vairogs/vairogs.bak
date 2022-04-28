<?php declare(strict_types = 1);

namespace Vairogs\Tests\Common\Adapter;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Cache\Adapter\DoctrineDbalAdapter;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Vairogs\Common\Adapter\Orm;
use Vairogs\Common\Adapter\PhpRedis;
use Vairogs\Common\Adapter\Predis;
use Vairogs\Utils\Helper\Composer;
use function sprintf;

class AdapterTest extends KernelTestCase
{
    public function testRedisAdapters(): void
    {
        $container = static::getContainer();

        $predis = new Predis(client: $container->get(id: 'snc_redis.predis'));
        $phpredis = new PhpRedis(client: $container->get(id: 'snc_redis.phpredis'));

        $this->assertInstanceOf(expected: Predis::class, actual: $predis);
        $this->assertInstanceOf(expected: PhpRedis::class, actual: $phpredis);

        $this->assertInstanceOf(expected: RedisAdapter::class, actual: $predis->getAdapter());
        $this->assertInstanceOf(expected: RedisAdapter::class, actual: $phpredis->getAdapter());
    }

    public function testRedisOrmAdapterException(): void
    {
        $container = static::getContainer();

        runkit7_method_copy(Composer::class, 'isInstalledOriginal', Composer::class, 'isInstalled');
        runkit7_method_copy(Composer::class, 'isInstalled', Composer::class, 'isInstalledOriginal');

        runkit7_method_redefine(Composer::class, 'isInstalled', 'array $packages, bool $incDevReq = true', 'return false;', (RUNKIT_ACC_PUBLIC | RUNKIT_ACC_STATIC));

        try {
            new PhpRedis(client: $container->get(id: 'snc_redis.phpredis'));
        } catch (InvalidConfigurationException $exception) {
            $this->assertEquals(expected: InvalidConfigurationException::class, actual: $exception::class);
        }

        try {
            new Orm($container->get('doctrine.orm.default_entity_manager'));
        } catch (InvalidConfigurationException $exception) {
            $this->assertEquals(expected: InvalidConfigurationException::class, actual: $exception::class);
        }

        runkit7_method_remove(Composer::class, 'isInstalled');
        runkit7_method_copy(Composer::class, 'isInstalled', Composer::class, 'isInstalledOriginal');
    }

    public function testOrmAdapter(): void
    {
        $container = static::getContainer();
        $orm = new Orm(entityManager: $em = $container->get(id: 'doctrine.orm.default_entity_manager'), namespace: __FUNCTION__);
        $this->assertInstanceOf(expected: Orm::class, actual: $orm);
        $this->assertInstanceOf(expected: DoctrineDbalAdapter::class, actual: $orm->getAdapter());
        $em->getConnection()->createSchemaManager()->dropTable(name: sprintf('%s_items', __FUNCTION__));
    }
}
