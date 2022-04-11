<?php declare(strict_types = 1);

namespace Vairogs\Twig\Pagination\Behaviour;

use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;
use function ceil;
use function floor;
use function range;
use function sprintf;

class FixedLength
{
    final public const MIN_VISIBLE = 3;

    public function __construct(private int $maxVisible)
    {
        $this->checkMinimum(maxVisible: $this->maxVisible);
    }

    public function withMaximumVisible(int $maxVisible): static
    {
        $this->checkMinimum(maxVisible: $maxVisible);

        $clone = clone $this;
        $clone->setMaximumVisible(maximumVisible: $maxVisible);

        return $clone;
    }

    public function getMaxVisible(): int
    {
        return $this->maxVisible;
    }

    public function getPaginationData(int $total, int $current, int $indicator = -1): array
    {
        $this->validate(total: $total, current: $current, indicator: $indicator);

        if ($total <= $this->maxVisible) {
            return range(start: 1, end: $total);
        }

        if ($this->hasSingleOmitted($total, $current)) {
            return $this->getDataWithSingleOmitted(total: $total, current: $current, indicator: $indicator);
        }

        return $this->getDataWithTwoOmitted(total: $total, current: $current, omitted: $indicator);
    }

    #[Pure]
    public function hasSingleOmitted(int $total, int $current): bool
    {
        return $this->hasSingleOmittedNearLast(current: $current) || $this->hasSingleOmittedNearStart(total: $total, current: $current);
    }

    private function checkMinimum(int $maxVisible): void
    {
        if ($this->maxVisible < self::MIN_VISIBLE) {
            throw new InvalidArgumentException(message: sprintf('Maximum of number of visible pages (%d) should be at least %d', $maxVisible, self::MIN_VISIBLE));
        }
    }

    private function setMaximumVisible(int $maximumVisible): void
    {
        $this->maxVisible = $maximumVisible;
    }

    private function validate(int $total, int $current, int $indicator = -1): void
    {
        if ($total < 1) {
            throw new InvalidArgumentException(message: sprintf('Total number of pages (%d) should not be lower than 1', $total));
        }

        if ($current < 1) {
            throw new InvalidArgumentException(message: sprintf('Current page (%d) should not be lower than 1', $current));
        }

        if ($current > $total) {
            throw new InvalidArgumentException(message: sprintf('Current page (%d) should not be higher than total number of pages (%d)', $current, $total));
        }

        if ($indicator >= 1 && $indicator <= $total) {
            throw new InvalidArgumentException(message: sprintf('Omitted pages indicator (%d) should not be between 1 and total number of pages (%d)', $indicator, $total));
        }
    }

    #[Pure]
    private function hasSingleOmittedNearLast(int $current): bool
    {
        return $current <= $this->getSingleBreakpoint();
    }

    #[Pure]
    private function getSingleBreakpoint(): int
    {
        return (int) floor(num: $this->maxVisible / 2) + 1;
    }

    #[Pure]
    private function hasSingleOmittedNearStart(int $total, int $current): bool
    {
        return $current >= $total - $this->getSingleBreakpoint() + 1;
    }

    #[Pure]
    private function getDataWithSingleOmitted(int $total, int $current, int $indicator): array
    {
        if ($this->hasSingleOmittedNearLast(current: $current)) {
            $rest = $this->maxVisible - $current;
            $omitPagesFrom = ((int) ceil(num: $rest / 2)) + $current;
            $omitPagesTo = $total - ($this->maxVisible - $omitPagesFrom);
        } else {
            $rest = $this->maxVisible - ($total - $current);
            $omitPagesFrom = (int) ceil(num: $rest / 2);
            $omitPagesTo = ($current - ($rest - $omitPagesFrom));
        }

        return [
            ...range(start: 1, end: $omitPagesFrom - 1),
            ...[$indicator],
            ...range(start: $omitPagesTo + 1, end: $total),
        ];
    }

    private function getDataWithTwoOmitted(int $total, int $current, int $omitted): array
    {
        $withoutCurrent = ($this->maxVisible - 1) / 2;

        if ($current <= ceil(num: $total / 2)) {
            $visibleLeft = ceil(num: $withoutCurrent);
            $visibleRight = floor(num: $withoutCurrent);
        } else {
            $visibleLeft = floor(num: $withoutCurrent);
            $visibleRight = ceil(num: $withoutCurrent);
        }

        $omitLeftFrom = floor(num: $visibleLeft / 2) + 1;
        $omitLeftTo = $current - ($visibleLeft - $omitLeftFrom) - 1;
        $omitRightFrom = ceil(num: $visibleRight / 2) + $current;
        $omitRightTo = $total - ($visibleRight - ($omitRightFrom - $current));

        return [
            ...range(start: 1, end: $omitLeftFrom - 1),
            ...[$omitted],
            ...range(start: $omitLeftTo + 1, end: $omitRightFrom - 1),
            ...[$omitted],
            ...range(start: $omitRightTo + 1, end: $total),
        ];
    }
}
