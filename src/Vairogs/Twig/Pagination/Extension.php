<?php declare(strict_types = 1);

namespace Vairogs\Twig\Pagination;

use Vairogs\Twig\Pagination\Behaviour\FixedLength;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Vairogs\Component\Utils\Twig\TwigTrait;
use Vairogs\Component\Utils\Vairogs;

class Extension extends AbstractExtension
{
    use TwigTrait;

    public function pagination(int $visible, int $total, int $current, int $ommit = -1): array
    {
        return (new FixedLength($visible))->getPaginationData($total, $current, $ommit);
    }

    public function getFunctions(): array
    {
        $input = [
            'pagination' => 'pagination',
        ];

        return $this->makeArray($input, Vairogs::VAIROGS, TwigFunction::class);
    }
}
