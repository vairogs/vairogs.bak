<?php declare(strict_types = 1);

namespace Vairogs\Assets;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use function dirname;
use function is_file;

class TestKernel extends BaseKernel
{
    use MicroKernelTrait;

    public function getProjectDir(): string
    {
        return dirname(path: __DIR__);
    }

    private function getConfigDir(): string
    {
        return $this->getProjectDir() . '/config';
    }

    private function getBundlesPath(): string
    {
        return $this->getConfigDir() . '/bundles.php';
    }

    private function configureContainer(ContainerConfigurator $container, LoaderInterface $loader, ContainerBuilder $builder): void
    {
        $configDir = $this->getConfigDir();

        $container->import($configDir . '/{packages}/*.{php,yaml}');
        $container->import($configDir . '/{packages}/' . $this->environment . '/*.{php,yaml}');

        if (is_file($configDir . '/services.yaml')) {
            $container->import($configDir . '/services.yaml');
            $container->import($configDir . '/{services}_' . $this->environment . '.yaml');
        } else {
            $container->import($configDir . '/{services}.php');
        }
    }
}
