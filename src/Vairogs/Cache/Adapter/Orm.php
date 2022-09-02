<?php declare(strict_types = 1);

namespace Vairogs\Cache\Adapter;

use Doctrine\DBAL\Exception as DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Cache\Adapter\DoctrineDbalAdapter;
use Vairogs\Core\Vairogs;
use Vairogs\Extra\Constants\Definition;

use function sprintf;

final class Orm extends AbstractAdapter
{
    protected string $class = self::class;
    protected array $packages = ['doctrine/dbal', 'doctrine/orm'];

    public function __construct(private readonly EntityManagerInterface $entityManager, private readonly string $namespace = Vairogs::VAIROGS)
    {
        $this->checkRequirements();
    }

    /**
     * @throws DBALException
     */
    public function getAdapter(int $defaultLifetime = Definition::DEFAULT_LIFETIME): CacheItemPoolInterface
    {
        $table = sprintf('%s_items', $this->namespace);
        $schemaManager = $this->entityManager->getConnection()->createSchemaManager();
        $doctrineDbalAdapter = new DoctrineDbalAdapter(connOrDsn: $this->entityManager->getConnection(), namespace: '', defaultLifetime: $defaultLifetime, options: ['db_table' => $table]);

        if (!$schemaManager->tablesExist(names: [$table])) {
            $doctrineDbalAdapter->createTable();
        }

        return $doctrineDbalAdapter;
    }
}
