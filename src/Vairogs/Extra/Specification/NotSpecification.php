<?php declare(strict_types = 1);

namespace Vairogs\Extra\Specification;

class NotSpecification extends CompositeSpecification
{
    /**
     * @param SpecificationInterface $specification
     */
    public function __construct(private SpecificationInterface $specification)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function isSatisfiedBy($expectedValue, $actualValue = null): bool
    {
        return !$this->specification->isSatisfiedBy($expectedValue);
    }
}
