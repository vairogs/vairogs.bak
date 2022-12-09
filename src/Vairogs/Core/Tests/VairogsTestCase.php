<?php declare(strict_types = 1);

namespace Vairogs\Core\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Vairogs\Core\Vairogs;
use Vairogs\Functions\File;

use function flock;
use function fopen;
use function is_dir;
use function sys_get_temp_dir;
use function touch;
use function unlink;

use const DIRECTORY_SEPARATOR;
use const LOCK_EX;
use const LOCK_NB;
use const LOCK_SH;

abstract class VairogsTestCase extends KernelTestCase
{
    final protected const LOCKFILE = 'vairogs-test-initialization-lock-file';
    protected ContainerInterface $container;
    protected string $directory;
    private static bool $initialized = false;

    protected function setUp(): void
    {
        $tmp = sys_get_temp_dir();

        if (!self::$initialized) {
            touch(filename: $tmpFile = $tmp . DIRECTORY_SEPARATOR . self::LOCKFILE);
            $lockFile = fopen(filename: $tmpFile, mode: 'rb');

            if (!flock(stream: $lockFile, operation: LOCK_EX | LOCK_NB)) {
                flock(stream: $lockFile, operation: LOCK_SH);
            }

            self::$initialized = true;
        }

        $this->directory = $tmp . DIRECTORY_SEPARATOR . Vairogs::VAIROGS . '-test';

        foreach ([$this->directory, $tmp . DIRECTORY_SEPARATOR . Vairogs::VAIROGS] as $dir) {
            if (is_dir(filename: $dir)) {
                (new File())->rmdir(directory: $dir);
            }
        }

        $this->container = static::getContainer();
    }

    protected function tearDown(): void
    {
        unlink(filename: sys_get_temp_dir() . DIRECTORY_SEPARATOR . self::LOCKFILE);
        self::$initialized = false;
    }
}
