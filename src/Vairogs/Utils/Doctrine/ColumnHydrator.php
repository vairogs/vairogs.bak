<?php declare(strict_types = 1);

namespace Vairogs\Utils\Doctrine;

use Doctrine\ORM\Internal\Hydration\ArrayHydrator;
use PDO;
use function array_column;
use function array_keys;
use function class_exists;
use function reset;

class ColumnHydrator extends ArrayHydrator
{
    protected function hydrateAllData(): array
    {
        if (!isset($this->_rsm->indexByMap['scalars']) && class_exists(class: PDO::class)) {
            return $this->_stmt->fetchAll(mode: PDO::FETCH_COLUMN);
        }

        if ([] === ($result = parent::hydrateAllData())) {
            return $result;
        }

        $indexColumn = $this->_rsm->scalarMappings[$this->_rsm->indexByMap['scalars']];
        $keys = array_keys(array: reset(array: $result));

        return array_column(array: $result, column_key: isset($keys[1]) && $indexColumn === $keys[0] ? $keys[1] : $keys[0], index_key: $indexColumn);
    }
}
