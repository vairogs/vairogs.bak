<?php declare(strict_types = 1);

namespace RavenFlux\Twig\Pagination;

use RavenFlux\Twig\Pagination\Behaviour\FixedLength;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PaginationExtension extends AbstractExtension
{
    /**
     * @param int $visible
     * @param int $total
     * @param int $current
     * @param int $ommit
     *
     * @return array
     */
    public function pagination(int $visible, int $total, int $current, int $ommit = -1): array
    {
        return (new FixedLength($visible))->getPaginationData($total, $current, $ommit);
    }

    /**
     * @return array
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('pagination', [
                $this,
                'pagination',
            ]),
        ];
    }
}
