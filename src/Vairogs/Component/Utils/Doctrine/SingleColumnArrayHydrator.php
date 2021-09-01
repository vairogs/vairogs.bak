<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\Doctrine;

use Doctrine\ORM\Internal\Hydration\AbstractHydrator;
use PDO;
use Vairogs\Component\Utils\Helper\Text;
use function is_numeric;
use function mb_strpos;

class SingleColumnArrayHydrator extends AbstractHydrator
{
    protected function hydrateAllData(): array
    {
        $result = [];

        while ($data = $this->_stmt->fetch(PDO::FETCH_NUM)) {
            $value = $data[0];

            if (is_numeric($value)) {
                $value = false === mb_strpos($value, '.', 0, Text::UTF8) ? (int)$value : (float)$value;
            }

            $result[] = $value;
        }

        return $result;
    }
}
