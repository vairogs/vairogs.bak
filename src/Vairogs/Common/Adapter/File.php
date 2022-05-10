<?php declare(strict_types = 1);

namespace Vairogs\Common\Adapter;

use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Cache\Adapter\PhpFilesAdapter;
use Symfony\Component\Cache\Exception\CacheException;
use Vairogs\Common\Common;
use Vairogs\Core\Vairogs;
use function sys_get_temp_dir;
use const DIRECTORY_SEPARATOR;

final class File implements Adapter
{
    public function __construct(private ?string $directory = null, private readonly string $namespace = Vairogs::VAIROGS)
    {
        $this->directory ??= sys_get_temp_dir() . DIRECTORY_SEPARATOR . $this->namespace;
    }

    /**
     * @throws CacheException
     */
    public function getAdapter(): CacheItemPoolInterface
    {
        return new PhpFilesAdapter(namespace: $this->namespace, defaultLifetime: Common::DEFAULT_LIFETIME, directory: $this->directory);
    }
}
