<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\Doctrine;

use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Platforms\PostgreSQL100Platform;
use Doctrine\ORM\Query\SqlWalker;
use InvalidArgumentException;
use function func_get_args;
use function is_array;
use function preg_replace;
use function preg_replace_callback;
use function sprintf;

class SortableNullWalker extends SqlWalker
{
    public const NULLS_FIRST = 'NULLS FIRST';
    public const NULLS_LAST = 'NULLS LAST';
    public const FIELDS = self::class . '.fields';

    /**
     * @throws Exception
     */
    public function walkOrderByClause($orderByClause): string|array|null
    {
        $sql = parent::walkOrderByClause($orderByClause);

        // @formatter:off
        $fields = $this->getQuery()->getHint(self::FIELDS);
        $platform = $this->getConnection()?->getDatabasePlatform()?->getName();
        // @formatter:on

        $mysql = (new MySqlPlatform())->getName();
        $postgres = (new PostgreSQL100Platform())->getName();
        $oracle = (new PostgreSQL100Platform())->getName();

        if (is_array($fields) && $platform) {
            foreach ($fields as $field => $sorting) {
                $sql = match ($platform) {
                    $mysql => $this->stepMysql($sql, $field, $sorting),
                    $oracle => $this->stepOracle($sql, $field, $sorting),
                    $postgres => $this->stepPostgre($sql, $field, $sorting),
                    default => throw new InvalidArgumentException(sprintf('Walker not implemented for "%s" platform', $platform)),
                };
            }
        }

        return $sql;
    }

    private function stepMysql(string $sql, string $field, string $sorting): string
    {
        if (self::NULLS_LAST === $sorting) {
            return preg_replace_callback('/ORDER BY (.+)' . '(' . $field . ') (' . Criteria::ASC . '|' . Criteria::DESC . ')/i', static function ($matches): string {
                if (Criteria::ASC === $matches[3]) {
                    $order = Criteria::DESC;
                } elseif (Criteria::DESC === $matches[3]) {
                    $order = Criteria::ASC;
                } else {
                    throw new InvalidArgumentException(sprintf('Order must be "%s" or "%s"', Criteria::ASC, Criteria::DESC));
                }

                return ('ORDER BY -' . $matches[1] . $matches[2] . ' ' . $order);
            }, $sql);
        }

        return $sql;
    }

    private function stepOracle(string $sql, string $field, string $sorting): string
    {
        return preg_replace('/(\.' . $field . ') (' . Criteria::ASC . '|' . Criteria::DESC . ')?\s*/i', '$1 $2 ' . $sorting, $sql);
    }

    private function stepPostgre(string $sql, string $field, string $sorting): string
    {
        return $this->stepOracle(...func_get_args());
    }
}
