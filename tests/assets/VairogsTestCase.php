<?php declare(strict_types = 1);

namespace Vairogs\Assets;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Vairogs\Core\Vairogs;
use Vairogs\Utils\Helper\File;
use function flock;
use function fopen;
use function is_dir;
use function sys_get_temp_dir;
use function touch;
use const DIRECTORY_SEPARATOR;
use const LOCK_EX;
use const LOCK_NB;
use const LOCK_SH;

abstract class VairogsTestCase extends KernelTestCase
{
    protected ContainerInterface $container;
    private static bool $initialized = false;

    protected function setUp(): void
    {
        if (!self::$initialized) {
            touch(filename: $tmpFile = '/tmp/test-initialization-lock-file');
            $lockFile = fopen(filename: $tmpFile, mode: 'rb');

            if (!flock(stream: $lockFile, operation: LOCK_EX | LOCK_NB)) {
                flock(stream: $lockFile, operation: LOCK_SH);
            }

            self::$initialized = true;
        }

        if (is_dir(filename: $directory = sys_get_temp_dir() . DIRECTORY_SEPARATOR . Vairogs::VAIROGS)) {
            (new File())->rmdir(directory: $directory);
        }

        $this->container = static::getContainer();
    }
}
