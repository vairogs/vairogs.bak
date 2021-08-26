<?php declare(strict_types = 1);

namespace Vairogs\Component\Cache\Utils\Adapter;

use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Exception as DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Exception;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Cache\Adapter\PdoAdapter;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Vairogs\Component\Utils\Vairogs;
use function class_exists;
use function interface_exists;
use function sprintf;

class Orm implements Cache
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
        if (!interface_exists(Driver::class) || !class_exists(Query::class)) {
            throw new InvalidConfigurationException(sprintf('Packages %s and %s must be installed in order to use %s', 'doctrine/orm', 'doctrine/dbal', self::class));
        }
    }

    /**
     * @throws DBALException
     */
    public function getAdapter(): CacheItemPoolInterface
    {
        $table = sprintf('%s_items', Vairogs::VAIROGS);
        $schemaManager = $this->entityManager->getConnection()
            ->getSchemaManager();
        $pdoAdapter = new PdoAdapter($this->entityManager->getConnection(), '', 0, ['db_table' => $table]);

        if ($schemaManager && !$schemaManager->tablesExist([$table])) {
            try {
                $pdoAdapter->createTable();
            } catch (Exception $exception) {
                throw new DBALException($exception->getMessage(), $exception->getCode(), $exception);
            }
        }

        if ($schemaManager && $schemaManager->tablesExist([$table])) {
            return $pdoAdapter;
        }

        throw DBALException::invalidTableName($table);
    }
}
