<?php declare(strict_types = 1);

namespace Vairogs\Common;

use Symfony\Component\Cache\Adapter\ChainAdapter;
use Vairogs\Common\Adapter\File;

final class Common
{
    final public const DEFAULT_LIFETIME = 86400;
    final public const HASH_ALGORITHM = 'xxh128';

    public function getChainAdapter(string $class, int $defaultLifetime = self::DEFAULT_LIFETIME, ...$adapters): ChainAdapter
    {
        if ([] === $adapters) {
            $adapters[] = new File();
        }

        return new ChainAdapter(adapters: (new Pool())->createPool(class: $class, adapters: $adapters), defaultLifetime: $defaultLifetime);
    }
}
