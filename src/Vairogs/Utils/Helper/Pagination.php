<?php declare(strict_types = 1);

namespace Vairogs\Utils\Helper;

use Vairogs\Twig\Attribute\TwigFilter;
use Vairogs\Twig\Attribute\TwigFunction;
use Vairogs\Utils\Helper\Pagination\Behaviour;

final class Pagination
{
    #[TwigFunction]
    #[TwigFilter]
    public function pagination(int $visible, int $total, int $current, int $ommit = -1): array
    {
        return (new Behaviour(visible: $visible))->getPaginationData(total: $total, current: $current, indicator: $ommit);
    }
}
