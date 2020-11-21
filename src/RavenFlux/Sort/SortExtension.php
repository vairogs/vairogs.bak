<?php declare(strict_types = 1);

namespace RavenFlux\Sort;

use Doctrine\Common\Collections\Collection;
use InvalidArgumentException;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Vairogs\Component\Utils\Helper\Sort;
use Vairogs\Component\Utils\Twig\TwigTrait;
use Vairogs\Component\Utils\Vairogs;
use function count;
use function current;
use function is_array;
use function strtoupper;
use function usort;

class SortExtension extends AbstractExtension
{
    use TwigTrait;

    /**
     * @return TwigFilter[]
     */
    public function getFilters(): array
    {
        $input = [
            'usort' => 'usortFunction',
        ];

        return $this->makeArray($input, Vairogs::RAVEN, TwigFilter::class);
    }

    /**
     * @param mixed $data
     * @param null|string $parameter
     * @param string $order
     * @return array
     */
    public function usortFunction($data, ?string $parameter = null, string $order = Sort::ASC): array
    {
        if ($data instanceof Collection) {
            $data = $data->toArray();
        }

        if (!is_array($data)) {
            throw new InvalidArgumentException('Only iterable variables can be sorted');
        }

        if (count($data) < 2) {
            return $data;
        }

        if (null === $parameter) {
            throw new InvalidArgumentException('No sorting parameter pased');
        }

        if (!Sort::isSortable(current($data), $parameter)) {
            throw new InvalidArgumentException('Sorting parameter doesn\'t exist in sortable variable');
        }

        @usort($data, Sort::usort($parameter, strtoupper($order)));

        return $data;
    }
}
