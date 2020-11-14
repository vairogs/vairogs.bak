<?php declare(strict_types = 1);

namespace Vairogs\Component\Cache;

use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Vairogs\Component\Cache\DependencyInjection\VairogsCacheExtension;

class Cache extends Bundle
{
    /**
     * @var string
     */
    public const CACHE_HEADER = 'Vairogs-Cache';

    /**
     * @var string
     */
    public const INVALIDATE_CACHE = 'invalidate';

    /**
     * @var string
     */
    public const SKIP_CACHE = 'skip';

    /**
     * @var string
     */
    public const ALIAS = 'cache';

    /**
     * @return null|ExtensionInterface
     */
    public function getContainerExtension(): ?ExtensionInterface
    {
        return $this->extension ?? new VairogsCacheExtension();
    }
}
