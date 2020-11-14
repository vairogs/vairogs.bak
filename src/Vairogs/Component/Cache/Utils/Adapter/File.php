<?php declare(strict_types = 1);

namespace Vairogs\Component\Cache\Utils\Adapter;

use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Cache\Adapter\PhpFilesAdapter;
use Symfony\Component\Cache\Exception\CacheException;
use Vairogs\Component\Utils\Vairogs;

class File implements Cache
{
    /**
     * @return CacheItemPoolInterface
     * @throws CacheException
     */
    public function getAdapter(): CacheItemPoolInterface
    {
        return new PhpFilesAdapter(Vairogs::ALIAS, 0);
    }
}
