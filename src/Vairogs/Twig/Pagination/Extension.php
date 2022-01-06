<?php declare(strict_types = 1);

namespace Vairogs\Twig\Pagination;

use Vairogs\Twig\Pagination\Behaviour\FixedLength;
use Vairogs\Utils\Twig\Attribute;
use Vairogs\Utils\Twig\BaseExtension;

class Extension extends BaseExtension
{
    protected static string $class = self::class;

    #[Attribute\TwigFunction]
    public function pagination(int $visible, int $total, int $current, int $ommit = -1): array
    {
        return (new FixedLength(maximumVisible: $visible))->getPaginationData(totalPages: $total, currentPage: $current, indicator: $ommit);
    }
}
