<?php declare(strict_types = 1);

namespace Vairogs\Tests\Source;

use Exception;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Vairogs\Cache\Adapter\File;
use Vairogs\Cache\Adapter\Orm;
use Vairogs\Cache\Adapter\Predis;
use Vairogs\Tests\Assets\VairogsTestCase;
use Vairogs\Utils\Helper\Composer;

class RemovedFunctionsTest extends VairogsTestCase
{
    /**
     * @noinspection PhpUndefinedConstantInspection
     * @noinspection PhpUndefinedFunctionInspection
     * @noinspection MissingService
     * @noinspection PhpParamsInspection
     */
    public function testMissingRequirements(): void
    {
        runkit7_method_copy(Composer::class, 'isInstalledOriginal', Composer::class, 'isInstalled');
        runkit7_method_copy(Composer::class, 'isInstalled', Composer::class, 'isInstalledOriginal');

        runkit7_method_redefine(Composer::class, 'isInstalled', 'array $packages, bool $incDevReq = true', 'return false;', RUNKIT_ACC_PUBLIC | RUNKIT_ACC_STATIC);

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
