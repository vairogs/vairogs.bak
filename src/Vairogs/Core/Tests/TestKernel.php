<?php declare(strict_types = 1);

namespace Vairogs\Core\Tests;

use ReflectionObject;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

use function getcwd;
use function hash;
use function sys_get_temp_dir;

class TestKernel extends Kernel
{
    use MicroKernelTrait;

    public function getProjectDir(): string
    {
        return getcwd();
    }

    public function getCacheDir(): string
    {
        /* @noinspection ProjectDirParameter */
        return sys_get_temp_dir() . '/Vairogs/' . hash(algo: 'xxh128', data: getcwd()) . '/var/cache';
    }

    protected function getConfigDir(): string
    {
        /* @noinspection ProjectDirParameter */
        return $this->getProjectDir() . '/Tests/Resources/config';
    }

    protected function getBundlesPath(): string
    {
        return $this->getConfigDir() . '/bundles.php';
    }

    /** @noinspection PhpUnusedParameterInspection */
    protected function configureContainer(ContainerConfigurator $container, LoaderInterface $loader, ContainerBuilder $builder): void
    {
        $configDir = $this->getConfigDir();

        $container->import(resource: $configDir . '/{packages}/*.{php}');
        $container->import(resource: $configDir . '/{packages}/' . $this->getEnvironment() . '/*.{php}');
        $container->import(resource: $configDir . '/{services}.php');
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $configDir = $this->getConfigDir();

        $routes->import(resource: $configDir . '/{routes}/' . $this->getEnvironment() . '/*.{php}');
        $routes->import(resource: $configDir . '/{routes}/*.{php}');
        $routes->import(resource: $configDir . '/{routes}.php');

        if (false !== ($fileName = (new ReflectionObject($this))->getFileName())) {
            $routes->import(resource: $fileName, type: 'attribute');
        }
    }
}
