<?php declare(strict_types = 1);

namespace Vairogs\Extra\Specification;

interface SpecificationInterface
{
    public function isSatisfiedBy($expectedValue, $actualValue = null): bool;

    public function andX(SpecificationInterface $specification): SpecificationInterface;

    public function orX(SpecificationInterface $specification): SpecificationInterface;

    public function not(): SpecificationInterface;
}
