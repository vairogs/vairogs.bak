<?php declare(strict_types = 1);

namespace Vairogs\Twig\Pagination;

use Vairogs\Component\Utils\Twig\Annotation;
use Vairogs\Component\Utils\Twig\BaseExtension;
use Vairogs\Twig\Pagination\Behaviour\FixedLength;

class Extension extends BaseExtension
{
    protected static string $class = self::class;

    #[Annotation\TwigFunction]
    public function pagination(int $visible, int $total, int $current, int $ommit = -1): array
    {
        return (new FixedLength($visible))->getPaginationData($total, $current, $ommit);
    }
}
