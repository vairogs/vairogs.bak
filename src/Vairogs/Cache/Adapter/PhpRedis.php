<?php declare(strict_types = 1);

namespace Vairogs\Cache\Adapter;

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Vairogs\Utils\Helper\Composer;
use function implode;
use function sprintf;

final class PhpRedis extends AbstractRedisAdapter
{
    protected function checkDeclaration(): ?string
    {
        if (!Composer::isInstalled(packages: $packages = ['ext-redis'], includeDevRequirements: false)) {
            throw new InvalidConfigurationException(message: sprintf(self::MESSAGE, self::class, implode(separator: ',', array: $packages)));
        }

        return null;
    }
}
