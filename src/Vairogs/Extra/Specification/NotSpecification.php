<?php declare(strict_types = 1);

namespace Vairogs\Extra\Specification;

class NotSpecification extends CompositeSpecification
{
    public function __construct(private readonly SpecificationInterface $specification)
    {
    }

    public function isSatisfiedBy(mixed $expectedValue, mixed $actualValue = null): bool
    {
        return !$this->specification->isSatisfiedBy(expectedValue: $expectedValue);
    }
}
