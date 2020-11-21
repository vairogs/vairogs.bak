<?php declare(strict_types = 1);

namespace RavenFlux\Pagination;

use RavenFlux\Pagination\Behaviour\FixedLength;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Vairogs\Component\Utils\Twig\TwigTrait;
use Vairogs\Component\Utils\Vairogs;

class PaginationExtension extends AbstractExtension
{
    use TwigTrait;

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
        $input = [
            'pagination' => 'pagination',
        ];

        return $this->makeArray($input, Vairogs::RAVEN, TwigFunction::class);
    }
}
