<?php declare(strict_types = 1);

namespace Vairogs\Extra\Specification;

interface SpecificationInterface
{
    public function isSatisfiedBy(mixed $expectedValue, mixed $actualValue = null): bool;

    public function andX(self $specification): self;

    public function orX(self $specification): self;

    public function not(): self;
}
