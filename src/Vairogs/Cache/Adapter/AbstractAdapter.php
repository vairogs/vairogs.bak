<?php declare(strict_types = 1);

namespace Vairogs\Cache\Adapter;

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Vairogs\Utils\Helper\Composer;

use function implode;
use function sprintf;

abstract class AbstractAdapter implements Adapter
{
    protected string $class;
    protected array $packages;

    protected function checkRequirements(): void
    {
        if (!(new Composer())->isInstalled(packages: $this->packages)) {
            throw new InvalidConfigurationException(message: sprintf('In order to use %s, package(s)/extension(s) "%s" must be installed', $this->class, implode(separator: ',', array: $this->packages)));
        }
    }
}
