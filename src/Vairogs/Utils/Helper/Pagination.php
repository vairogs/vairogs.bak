<?php declare(strict_types = 1);

namespace Vairogs\Utils\Helper;

use Vairogs\Twig\Attribute;

final class Pagination
{
    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function pagination(int $visible, int $total, int $current, int $ommit = -1): array
    {
        return (new Pagination\Behaviour(visible: $visible))->getPaginationData(total: $total, current: $current, indicator: $ommit);
    }
}
