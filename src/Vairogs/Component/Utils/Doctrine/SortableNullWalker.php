<?php declare(strict_types = 1);

namespace Vairogs\Utils\Doctrine;

use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\Query\AST\OrderByClause;
use Doctrine\ORM\Query\SqlWalker;
use InvalidArgumentException;
use function is_array;
use function preg_replace;
use function preg_replace_callback;
use function sprintf;

class SortableNullWalker extends SqlWalker
{
    public const NULLS_FIRST = 'NULLS FIRST';
    public const NULLS_LAST = 'NULLS LAST';

    public const FIELDS = __CLASS__ . '.fields';

    /**
     * @param OrderByClause $orderByClause
     *
     * @return string|string[]|null
     * @throws DBALException
     */
    public function walkOrderByClause($orderByClause)
    {
        $sql = parent::walkOrderByClause($orderByClause);

        if (is_array($fields = $this->getQuery()->getHint(self::FIELDS))) {
            $platform = $this->getConnection()->getDatabasePlatform()->getName();
            switch ($platform) {
                case 'mysql':
                    foreach ($fields as $field => $sorting) {
                        if (self::NULLS_LAST === $sorting) {
                            $sql = preg_replace_callback('/ORDER BY (.+)' . '(' . $field . ') (ASC|DESC)/i', static function ($matches) {
                                if ($matches[3] === Criteria::ASC) {
                                    $order = Criteria::DESC;
                                } elseif ($matches[3] === Criteria::DESC) {
                                    $order = Criteria::ASC;
                                } else {
                                    throw new InvalidArgumentException(sprintf('Order must be "%s" or "%s"', Criteria::ASC, Criteria::DESC));
                                }

                                return ('ORDER BY -' . $matches[1] . $matches[2] . ' ' . $order);
                            }, $sql);
                        }
                    }
                    break;
                case 'oracle':
                case 'postgresql':
                    foreach ($fields as $field => $sorting) {
                        $sql = preg_replace('/(\.' . $field . ') (ASC|DESC)?\s*/i', '$1 $2 ' . $sorting, $sql);
                    }
                    break;
                default:
                    throw new InvalidArgumentException(sprintf('Walker not implemented for "%s" platform', $platform));
                    break;
            }
        }

        return $sql;
    }
}
