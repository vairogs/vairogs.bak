<?php declare(strict_types = 1);

namespace Vairogs\Common\Adapter;

use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Cache\Adapter\PhpFilesAdapter;
use Symfony\Component\Cache\Exception\CacheException;
use Vairogs\Common\Common;
use Vairogs\Core\Vairogs;

final class File implements Adapter
{
    public function __construct(private readonly string $namespace = Vairogs::VAIROGS)
    {
    }

    /**
     * @throws CacheException
     */
    public function getAdapter(): CacheItemPoolInterface
    {
        return new PhpFilesAdapter(namespace: $this->namespace, defaultLifetime: Common::DEFAULT_LIFETIME);
    }
}
