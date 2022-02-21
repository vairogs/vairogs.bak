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
    public const MIN_VISIBLE = 3;

    public function __construct(private int $maximumVisible)
    {
        $this->checkMinimumAllowed(maximumVisible: $this->maximumVisible);
    }

    public function withMaximumVisible(int $maximumVisible): static
    {
        $this->checkMinimumAllowed(maximumVisible: $maximumVisible);

        $clone = clone $this;
        $clone->setMaximumVisible(maximumVisible: $maximumVisible);

        return $clone;
    }

    public function getMaximumVisible(): int
    {
        return $this->maximumVisible;
    }

    public function getPaginationData(int $totalPages, int $currentPage, int $indicator = -1): array
    {
        $this->validate(totalPages: $totalPages, currentPage: $currentPage, indicator: $indicator);

        if ($totalPages <= $this->maximumVisible) {
            return range(start: 1, end: $totalPages);
        }

        if ($this->hasSingleOmittedChunk($totalPages, $currentPage)) {
            return $this->getPaginationDataWithSingleOmittedChunk(totalPages: $totalPages, currentPage: $currentPage, omittedPagesIndicator: $indicator);
        }

        return $this->getPaginationDataWithTwoOmittedChunks(totalPages: $totalPages, currentPage: $currentPage, omittedPagesIndicator: $indicator);
    }

    #[Pure]
    public function hasSingleOmittedChunk(int $totalPages, int $currentPage): bool
    {
        return $this->hasSingleOmittedChunkNearLastPage(currentPage: $currentPage) || $this->hasSingleOmittedChunkNearStartPage(totalPages: $totalPages, currentPage: $currentPage);
    }

    private function checkMinimumAllowed(int $maximumVisible): void
    {
        if ($this->maximumVisible < self::MIN_VISIBLE) {
            throw new InvalidArgumentException(message: sprintf('Maximum of number of visible pages (%d) should be at least %d', $maximumVisible, self::MIN_VISIBLE));
        }
    }

    private function setMaximumVisible(int $maximumVisible): void
    {
        $this->maximumVisible = $maximumVisible;
    }

    private function validate(int $totalPages, int $currentPage, int $indicator = -1): void
    {
        if ($totalPages < 1) {
            throw new InvalidArgumentException(message: sprintf('Total number of pages (%d) should not be lower than 1', $totalPages));
        }

        if ($currentPage < 1) {
            throw new InvalidArgumentException(message: sprintf('Current page (%d) should not be lower than 1', $currentPage));
        }

        if ($currentPage > $totalPages) {
            throw new InvalidArgumentException(message: sprintf('Current page (%d) should not be higher than total number of pages (%d)', $currentPage, $totalPages));
        }

        if ($indicator >= 1 && $indicator <= $totalPages) {
            throw new InvalidArgumentException(message: sprintf('Omitted pages indicator (%d) should not be between 1 and total number of pages (%d)', $indicator, $totalPages));
        }
    }

    #[Pure]
    private function hasSingleOmittedChunkNearLastPage(int $currentPage): bool
    {
        return $currentPage <= $this->getSingleOmissionBreakpoint();
    }

    #[Pure]
    private function getSingleOmissionBreakpoint(): int
    {
        return (int) floor(num: $this->maximumVisible / 2) + 1;
    }

    #[Pure]
    private function hasSingleOmittedChunkNearStartPage(int $totalPages, int $currentPage): bool
    {
        return $currentPage >= $totalPages - $this->getSingleOmissionBreakpoint() + 1;
    }

    #[Pure]
    private function getPaginationDataWithSingleOmittedChunk(int $totalPages, int $currentPage, int $omittedPagesIndicator): array
    {
        if ($this->hasSingleOmittedChunkNearLastPage(currentPage: $currentPage)) {
            $rest = $this->maximumVisible - $currentPage;
            $omitPagesFrom = ((int) ceil(num: $rest / 2)) + $currentPage;
            $omitPagesTo = $totalPages - ($this->maximumVisible - $omitPagesFrom);
        } else {
            $rest = $this->maximumVisible - ($totalPages - $currentPage);
            $omitPagesFrom = (int) ceil(num: $rest / 2);
            $omitPagesTo = ($currentPage - ($rest - $omitPagesFrom));
        }

        return [
            ...range(start: 1, end: $omitPagesFrom - 1),
            ...[$omittedPagesIndicator],
            ...range(start: $omitPagesTo + 1, end: $totalPages),
        ];
    }

    private function getPaginationDataWithTwoOmittedChunks(int $totalPages, int $currentPage, int $omittedPagesIndicator): array
    {
        $visibleExceptForCurrent = ($this->maximumVisible - 1) / 2;

        if ($currentPage <= ceil(num: $totalPages / 2)) {
            $visibleLeft = ceil(num: $visibleExceptForCurrent);
            $visibleRight = floor(num: $visibleExceptForCurrent);
        } else {
            $visibleLeft = floor(num: $visibleExceptForCurrent);
            $visibleRight = ceil(num: $visibleExceptForCurrent);
        }

        $omitPagesLeftFrom = floor(num: $visibleLeft / 2) + 1;
        $omitPagesLeftTo = $currentPage - ($visibleLeft - $omitPagesLeftFrom) - 1;
        $omitPagesRightFrom = ceil(num: $visibleRight / 2) + $currentPage;
        $omitPagesRightTo = $totalPages - ($visibleRight - ($omitPagesRightFrom - $currentPage));

        return [
            ...range(start: 1, end: $omitPagesLeftFrom - 1),
            ...[$omittedPagesIndicator],
            ...range(start: $omitPagesLeftTo + 1, end: $omitPagesRightFrom - 1),
            ...[$omittedPagesIndicator],
            ...range(start: $omitPagesRightTo + 1, end: $totalPages),
        ];
    }
}
