<?php declare(strict_types = 1);

namespace RavenFlux\Pagination\Behaviour;

use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;

final class FixedLength
{
    /**
     * @var int
     */
    public const MIN_VISIBLE = 3;

    private int $maximumVisible;

    /**
     * @param int $maximumVisible
     */
    public function __construct(int $maximumVisible)
    {
        $this->setMaximumVisible($maximumVisible);
    }

    /**
     * @param int $maximumVisible
     */
    private function setMaximumVisible(int $maximumVisible): void
    {
        if ($maximumVisible < self::MIN_VISIBLE) {
            throw new InvalidArgumentException(sprintf('Maximum of number of visible pages (%d) should be at least %d.', $maximumVisible, self::MIN_VISIBLE));
        }
        $this->maximumVisible = $maximumVisible;
    }

    /**
     * @param int $maximumVisible
     *
     * @return FixedLength
     */
    public function withMaximumVisible(int $maximumVisible): FixedLength
    {
        $clone = clone $this;
        $clone->setMaximumVisible($maximumVisible);

        return $clone;
    }

    /**
     * @return int
     */
    public function getMaximumVisible(): int
    {
        return $this->maximumVisible;
    }

    /**
     * @param int $totalPages
     * @param int $currentPage
     * @param int $indicator
     *
     * @return array
     */
    public function getPaginationData(int $totalPages, int $currentPage, int $indicator = -1): array
    {
        $this->guardPaginationData($totalPages, $currentPage, $indicator);
        if ($totalPages <= $this->maximumVisible) {
            return range(1, $totalPages);
        }
        if ($this->hasSingleOmittedChunk($totalPages, $currentPage)) {
            return $this->getPaginationDataWithSingleOmittedChunk($totalPages, $currentPage, $indicator);
        }

        return $this->getPaginationDataWithTwoOmittedChunks($totalPages, $currentPage, $indicator);
    }

    /**
     * @param int $totalPages
     * @param int $currentPage
     * @param int|string $indicator
     */
    private function guardPaginationData(int $totalPages, int $currentPage, int|string $indicator = -1): void
    {
        if ($totalPages < 1) {
            throw new InvalidArgumentException(sprintf('Total number of pages (%d) should not be lower than 1.', $totalPages));
        }
        if ($currentPage < 1) {
            throw new InvalidArgumentException(sprintf('Current page (%d) should not be lower than 1.', $currentPage));
        }
        if ($currentPage > $totalPages) {
            throw new InvalidArgumentException(sprintf('Current page (%d) should not be higher than total number of pages (%d).', $currentPage, $totalPages));
        }
        if (!is_int($indicator) && !is_string($indicator)) {
            throw new InvalidArgumentException('Omitted pages indicator should either be a string or an int.');
        }
        if ($indicator >= 1 && $indicator <= $totalPages) {
            throw new InvalidArgumentException(sprintf('Omitted pages indicator (%d) should not be between 1 and total number of pages (%d).', $indicator, $totalPages));
        }
    }

    /**
     * @param int $totalPages
     * @param int $currentPage
     *
     * @return bool
     */
    #[Pure] public function hasSingleOmittedChunk(int $totalPages, int $currentPage): bool
    {
        return $this->hasSingleOmittedChunkNearLastPage($currentPage) || $this->hasSingleOmittedChunkNearStartPage($totalPages, $currentPage);
    }

    /**
     * @param int $currentPage
     *
     * @return bool
     */
    #[Pure] private function hasSingleOmittedChunkNearLastPage(int $currentPage): bool
    {
        return $currentPage <= $this->getSingleOmissionBreakpoint();
    }

    /**
     * @return int
     */
    #[Pure] private function getSingleOmissionBreakpoint(): int
    {
        return (int)floor($this->maximumVisible / 2) + 1;
    }

    /**
     * @param int $totalPages
     * @param int $currentPage
     *
     * @return bool
     */
    #[Pure] private function hasSingleOmittedChunkNearStartPage(int $totalPages, int $currentPage): bool
    {
        return $currentPage >= $totalPages - $this->getSingleOmissionBreakpoint() + 1;
    }

    /**
     * @param int $totalPages
     * @param int $currentPage
     * @param int $omittedPagesIndicator
     *
     * @return array
     */
    private function getPaginationDataWithSingleOmittedChunk(int $totalPages, int $currentPage, int $omittedPagesIndicator): array
    {
        if ($this->hasSingleOmittedChunkNearLastPage($currentPage)) {
            $rest = $this->maximumVisible - $currentPage;
            $omitPagesFrom = ((int)ceil($rest / 2)) + $currentPage;
            $omitPagesTo = $totalPages - ($this->maximumVisible - $omitPagesFrom);
        } else {
            $rest = $this->maximumVisible - ($totalPages - $currentPage);
            $omitPagesFrom = (int)ceil($rest / 2);
            $omitPagesTo = ($currentPage - ($rest - $omitPagesFrom));
        }
        $pagesLeft = range(1, $omitPagesFrom - 1);
        $pagesRight = range($omitPagesTo + 1, $totalPages);

        return [...$pagesLeft, ...[$omittedPagesIndicator], ...$pagesRight];
    }

    /**
     * @param int $totalPages
     * @param int $currentPage
     * @param int $omittedPagesIndicator
     *
     * @return array
     */
    private function getPaginationDataWithTwoOmittedChunks(int $totalPages, int $currentPage, int $omittedPagesIndicator): array
    {
        $visibleExceptForCurrent = $this->maximumVisible - 1;
        if ($currentPage <= ceil($totalPages / 2)) {
            $visibleLeft = ceil($visibleExceptForCurrent / 2);
            $visibleRight = floor($visibleExceptForCurrent / 2);
        } else {
            $visibleLeft = floor($visibleExceptForCurrent / 2);
            $visibleRight = ceil($visibleExceptForCurrent / 2);
        }
        $omitPagesLeftFrom = floor($visibleLeft / 2) + 1;
        $omitPagesLeftTo = $currentPage - ($visibleLeft - $omitPagesLeftFrom) - 1;
        $omitPagesRightFrom = ceil($visibleRight / 2) + $currentPage;
        $omitPagesRightTo = $totalPages - ($visibleRight - ($omitPagesRightFrom - $currentPage));
        $pagesLeft = range(1, $omitPagesLeftFrom - 1);
        $pagesCenter = range($omitPagesLeftTo + 1, $omitPagesRightFrom - 1);
        $pagesRight = range($omitPagesRightTo + 1, $totalPages);

        return [...$pagesLeft, ...[$omittedPagesIndicator], ...$pagesCenter, ...[$omittedPagesIndicator], ...$pagesRight];
    }
}
