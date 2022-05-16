<?php declare(strict_types = 1);

namespace Vairogs\Assets;

use ReflectionObject;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Vairogs\Core\Vairogs;
use Vairogs\Utils\Helper\File;
use function dirname;
use function is_dir;
use function is_file;
use function sys_get_temp_dir;
use const DIRECTORY_SEPARATOR;

class TestKernel extends Kernel
{
    use MicroKernelTrait;

    public function getProjectDir(): string
    {
        return dirname(path: __DIR__);
    }

    /** @noinspection ProjectDirParameter */
    public function getCacheDir(): string
    {
        return $this->getProjectDir() . '/var/cache';
    }

    public function getTmpDir(): string
    {
        $directory = sys_get_temp_dir() . DIRECTORY_SEPARATOR . '_' . Vairogs::VAIROGS;

        if (!is_dir(filename: $directory)) {
            (new File())->mkdir(dir: $directory);
        }

        return $directory;
    }

    /** @noinspection ProjectDirParameter */
    protected function getConfigDir(): string
    {
        return $this->getProjectDir() . '/config';
    }

    protected function getBundlesPath(): string
    {
        return $this->getConfigDir() . '/bundles.php';
    }

    /** @noinspection PhpUnusedParameterInspection */
    protected function configureContainer(ContainerConfigurator $container, LoaderInterface $loader, ContainerBuilder $builder): void
    {
        $configDir = $this->getConfigDir();

        $container->import(resource: $configDir . '/{packages}/*.{php,yaml}');
        $container->import(resource: $configDir . '/{packages}/' . $this->getEnvironment() . '/*.{php,yaml}');

        if (is_file(filename: $configDir . '/services.yaml')) {
            $container->import(resource: $configDir . '/services.yaml');
            $container->import(resource: $configDir . '/{services}_' . $this->getEnvironment() . '.yaml');
        } else {
            $container->import(resource: $configDir . '/{services}.php');
        }
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $configDir = $this->getConfigDir();

        $routes->import(resource: $configDir . '/{routes}/' . $this->getEnvironment() . '/*.{php,yaml}');
        $routes->import(resource: $configDir . '/{routes}/*.{php,yaml}');

        if (is_file(filename: $configDir . '/routes.yaml')) {
            $routes->import(resource: $configDir . '/routes.yaml');
        } else {
            $routes->import(resource: $configDir . '/{routes}.php');
        }

        if (false !== ($fileName = (new ReflectionObject($this))->getFileName())) {
            $routes->import(resource: $fileName, type: 'annotation');
        }
    }
}
