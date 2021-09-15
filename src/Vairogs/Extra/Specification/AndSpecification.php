<?php declare(strict_types = 1);

namespace Vairogs\Extra\Specification;

class AndSpecification extends CompositeSpecification
{
    public function __construct(private SpecificationInterface $one, private SpecificationInterface $other)
    {
    }

    public function isSatisfiedBy(mixed $expectedValue, mixed $actualValue = null): bool
    {
        return $this->one->isSatisfiedBy(expectedValue: $expectedValue) && $this->other->isSatisfiedBy(expectedValue: $expectedValue);
    }
}
