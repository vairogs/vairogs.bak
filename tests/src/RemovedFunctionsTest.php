<?php declare(strict_types = 1);

namespace Vairogs\Tests;

use Exception;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Vairogs\Assets\VairogsTestCase;
use Vairogs\Common\Adapter\File;
use Vairogs\Common\Adapter\Orm;
use Vairogs\Common\Adapter\PhpRedis;
use Vairogs\Common\Adapter\Predis;
use Vairogs\Utils\Helper\Composer;

class RemovedFunctionsTest extends VairogsTestCase
{
    /**
     * @noinspection PhpUndefinedConstantInspection
     * @noinspection PhpUndefinedFunctionInspection
     */
    public function testMissingRequirements(): void
    {
        runkit7_method_copy(Composer::class, 'isInstalledOriginal', Composer::class, 'isInstalled');
        runkit7_method_copy(Composer::class, 'isInstalled', Composer::class, 'isInstalledOriginal');

        runkit7_method_redefine(Composer::class, 'isInstalled', 'array $packages, bool $incDevReq = true', 'return false;', (RUNKIT_ACC_PUBLIC | RUNKIT_ACC_STATIC));

        try {
            new PhpRedis(client: $this->container->get(id: 'snc_redis.phpredis'));
        } catch (Exception $exception) {
            $this->assertEquals(expected: InvalidConfigurationException::class, actual: $exception::class);
        }

        try {
            new Predis(client: $this->container->get(id: 'snc_redis.predis'));
        } catch (Exception $exception) {
            $this->assertEquals(expected: InvalidConfigurationException::class, actual: $exception::class);
        }

        try {
            new Orm($this->container->get('doctrine.orm.default_entity_manager'));
        } catch (Exception $exception) {
            $this->assertEquals(expected: InvalidConfigurationException::class, actual: $exception::class);
        }

        try {
            new File();
        } catch (Exception $exception) {
            $this->assertEquals(expected: InvalidConfigurationException::class, actual: $exception::class);
        }

        runkit7_method_remove(Composer::class, 'isInstalled');
        runkit7_method_copy(Composer::class, 'isInstalled', Composer::class, 'isInstalledOriginal');
    }
}
