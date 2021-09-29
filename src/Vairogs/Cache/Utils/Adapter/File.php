<?php declare(strict_types = 1);

namespace Vairogs\Cache\Utils\Adapter;

use Psr\Cache\CacheItemPoolInterface;
use Symfony\Cache\Adapter\PhpFilesAdapter;
use Symfony\Cache\Exception\CacheException;
use Vairogs\Component\Utils\Vairogs;

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
