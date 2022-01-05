<?php declare(strict_types = 1);

namespace Vairogs\Utils\Doctrine;

use Doctrine\ORM\Internal\Hydration\AbstractHydrator;
use LogicException;
use PDO;
use Vairogs\Utils\Helper\Text;
use function class_exists;
use function sprintf;

class SingleColumnArrayHydrator extends AbstractHydrator
{
    protected function hydrateAllData(): array
    {
        $result = [];

        if (!class_exists(class: PDO::class)) {
            throw new LogicException(message: sprintf('%s class (ext-pdo) is missing', PDO::class));
        }

        /* @noinspection PhpDeprecationInspection */
        while ($data = $this->_stmt->fetch(fetchMode: PDO::FETCH_NUM)) {
            $result[] = Text::getNormalizedValue(value: $data[0]);
        }

        return $result;
    }
}
