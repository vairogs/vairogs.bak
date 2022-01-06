<?php declare(strict_types = 1);

namespace Vairogs\Cache\Utils\Adapter;

use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Exception as DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Exception;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Cache\Adapter\PdoAdapter;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Vairogs\Utils\Helper\Php;
use Vairogs\Utils\Vairogs;
use function sprintf;

class Orm implements Adapter
{
    public function __construct(private EntityManagerInterface $entityManager, private string $namespace = Vairogs::VAIROGS)
    {
        if (!Php::exists(class: Driver::class) || !Php::exists(class: Query::class)) {
            throw new InvalidConfigurationException(message: sprintf('Packages %s and %s must be installed in order to use %s', 'doctrine/orm', 'doctrine/dbal', self::class));
        }
    }

    /**
     * @throws DBALException
     */
    public function getAdapter(): CacheItemPoolInterface
    {
        $table = sprintf('%s_items', $this->namespace);
        $schemaManager = $this->entityManager->getConnection()
            ->getSchemaManager();
        $pdoAdapter = new PdoAdapter(connOrDsn: $this->entityManager->getConnection(), namespace: '', defaultLifetime: 0, options: ['db_table' => $table]);

        if ($schemaManager && !$schemaManager->tablesExist(names: [$table])) {
            try {
                $pdoAdapter->createTable();
            } catch (Exception $exception) {
                throw new DBALException(message: $exception->getMessage(), code: $exception->getCode(), previous: $exception);
            }
        }

        if ($schemaManager && $schemaManager->tablesExist(names: [$table])) {
            return $pdoAdapter;
        }

        throw DBALException::invalidTableName(tableName: $table);
    }
}
