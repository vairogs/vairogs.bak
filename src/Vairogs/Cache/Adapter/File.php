<?php declare(strict_types = 1);

namespace Vairogs\Cache\Adapter;

use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Cache\Adapter\PhpFilesAdapter;
use Symfony\Component\Cache\Exception\CacheException;
use Vairogs\Core\Vairogs;
use Vairogs\Extra\Constants\Definition;

use function sys_get_temp_dir;

use const DIRECTORY_SEPARATOR;

final class File extends AbstractAdapter
{
    protected string $class = self::class;
    protected array $packages = ['Zend OPcache'];

    public function __construct(private ?string $directory = null, private readonly string $namespace = Vairogs::VAIROGS)
    {
        $this->checkRequirements();
        $this->directory ??= sys_get_temp_dir() . DIRECTORY_SEPARATOR . $this->namespace;
    }

    /**
     * @throws CacheException
     */
    public function getAdapter(int $defaultLifetime = Definition::DEFAULT_LIFETIME): CacheItemPoolInterface
    {
        return new PhpFilesAdapter(namespace: $this->namespace, defaultLifetime: $defaultLifetime, directory: $this->directory, appendOnly: true);
    }
}
