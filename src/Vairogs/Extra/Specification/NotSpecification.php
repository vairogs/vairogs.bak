<?php declare(strict_types = 1);

namespace Vairogs\Extra\Specification;

use const null;

class NotSpecification extends CompositeSpecification
{
    private SpecificationInterface $specification;

    /**
     * @param SpecificationInterface $specification
     */
    public function __construct(SpecificationInterface $specification)
    {
        $this->specification = $specification;
    }

    /**
     * {@inheritdoc}
     */
    public function isSatisfiedBy($expectedValue, $actualValue = null): bool
    {
        return !$this->specification->isSatisfiedBy($expectedValue);
    }
}
