<?php declare(strict_types = 1);

namespace Vairogs\Core\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Vairogs\Core\Vairogs;

use function sys_get_temp_dir;

use const DIRECTORY_SEPARATOR;

abstract class VairogsTestCase extends KernelTestCase
{
    protected ContainerInterface $container;
    protected string $directory;

    protected function setUp(): void
    {
        $this->directory = sys_get_temp_dir() . DIRECTORY_SEPARATOR . Vairogs::VAIROGS . '-test';
        $this->container = static::getContainer();
    }
}
