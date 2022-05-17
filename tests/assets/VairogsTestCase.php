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
    protected string $tmp;
    private static bool $initialized = false;

    protected function setUp(): void
    {
        $this->tmp = sys_get_temp_dir() . DIRECTORY_SEPARATOR;

        if (!self::$initialized) {
            touch(filename: $tmpFile = $this->tmp . self::LOCKFILE);
            $lockFile = fopen(filename: $tmpFile, mode: 'rb');

            if (!flock(stream: $lockFile, operation: LOCK_EX | LOCK_NB)) {
                flock(stream: $lockFile, operation: LOCK_SH);
            }

            self::$initialized = true;
        }

        if (is_dir(filename: $this->directory = $this->tmp . Vairogs::VAIROGS)) {
            (new File())->rmdir(directory: $this->directory);
        }

        $this->container = static::getContainer();
    }

    protected function tearDown(): void
    {
        @unlink(filename: $this->tmp . self::LOCKFILE);
        self::$initialized = false;

        parent::tearDown();
    }
}
