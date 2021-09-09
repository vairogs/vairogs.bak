<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\Doctrine;

use Doctrine\ORM\Internal\Hydration\AbstractHydrator;
use PDO;
use RuntimeException;
use Vairogs\Component\Utils\Helper\Text;
use function class_exists;
use function sprintf;

class SingleColumnArrayHydrator extends AbstractHydrator
{
    protected function hydrateAllData(): array
    {
        $result = [];

        if (!class_exists(PDO::class)) {
            throw new RuntimeException(sprintf('%s class (ext-pdo) is missing', PDO::class));
        }

        while ($data = $this->_stmt->fetch(PDO::FETCH_NUM)) {
            $result[] = Text::getNormalizedValue($data[0]);
        }

        return $result;
    }
}
