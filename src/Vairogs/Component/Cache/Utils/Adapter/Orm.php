<?php declare(strict_types = 1);

namespace Vairogs\Component\Cache\Utils\Adapter;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Cache\Adapter\PdoAdapter;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Vairogs\Component\Utils\Vairogs;
use function class_exists;
use function interface_exists;
use function sprintf;

class Orm implements Cache
{
    private EntityManagerInterface $manager;

    /**
     * @param EntityManagerInterface $manager
     *
     * @throws InvalidConfigurationException
     */
    public function __construct(EntityManagerInterface $manager)
    {
        if (!interface_exists(Driver::class) || !class_exists(Query::class)) {
            throw new InvalidConfigurationException(sprintf('Packages %s and %s must be installed in order to use %s', 'doctrine/orm', 'doctrine/dbal', __CLASS__));
        }

        $this->manager = $manager;
    }

    /**
     * @return CacheItemPoolInterface
     * @throws Exception
     * @throws DBALException
     */
    public function getAdapter(): CacheItemPoolInterface
    {
        $table = sprintf('%s_items', Vairogs::ALIAS);
        $schema = $this->manager->getConnection()
            ->getSchemaManager();
        $adapter = new PdoAdapter($this->manager->getConnection(), '', 0, ['db_table' => $table]);

        if ($schema && !$schema->tablesExist([$table])) {
            $adapter->createTable();
        }

        if ($schema && $schema->tablesExist([$table])) {
            return $adapter;
        }

        throw Exception::invalidTableName($table);
    }
}
