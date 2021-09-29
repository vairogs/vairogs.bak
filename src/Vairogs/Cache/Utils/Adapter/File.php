<?php declare(strict_types = 1);

namespace Vairogs\Cache\Utils\Adapter;

use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Cache\Adapter\PhpFilesAdapter;
use Symfony\Component\Cache\Exception\CacheException;
use Vairogs\Utils\Vairogs;

class File implements Cache
{
    public function __construct(private string $namespace = Vairogs::VAIROGS)
    {
    }

    /**
     * @throws CacheException
     */
    public function getAdapter(): CacheItemPoolInterface
    {
        return new PhpFilesAdapter(namespace: $this->namespace, defaultLifetime: Cache::DEFAULT_LIFETIME);
    }
}
