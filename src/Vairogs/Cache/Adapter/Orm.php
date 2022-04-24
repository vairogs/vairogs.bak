<?php declare(strict_types = 1);

namespace Vairogs\Cache\Adapter;

use Doctrine\DBAL\Exception as DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Cache\Adapter\DoctrineDbalAdapter;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Vairogs\Cache\Cache;
use Vairogs\Core\Vairogs;
use Vairogs\Utils\Helper\Composer;
use function implode;
use function sprintf;

final class Orm implements Adapter
{
    public function __construct(private readonly EntityManagerInterface $entityManager, private readonly string $namespace = Vairogs::VAIROGS)
    {
        if (!Composer::isInstalled(packages: $packages = ['doctrine/dbal', 'doctrine/orm'], incDevReq: false)) {
            throw new InvalidConfigurationException(message: sprintf('In order to use %s, package(s)/extension(s) "%s" must be installed', self::class, implode(separator: ',', array: $packages)));
        }
    }

    /**
     * @throws DBALException
     */
    public function getAdapter(): CacheItemPoolInterface
    {
        $table = sprintf('%s_items', $this->namespace);
        $schemaManager = $this->entityManager->getConnection()->createSchemaManager();
        $dbalAdapter = new DoctrineDbalAdapter(connOrDsn: $this->entityManager->getConnection(), namespace: '', defaultLifetime: Cache::DEFAULT_LIFETIME, options: ['db_table' => $table]);

        if (!$schemaManager->tablesExist(names: [$table])) {
            $dbalAdapter->createTable();
        }

        if ($schemaManager->tablesExist(names: [$table])) {
            return $dbalAdapter;
        }

        throw new DBALException(message: sprintf('Invalid table: "%s"', $table));
    }
}
