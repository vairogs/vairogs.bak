<?php declare(strict_types = 1);

namespace Vairogs\Extra\Specification;

class AndSpecification extends CompositeSpecification
{
    /**
     * @param SpecificationInterface $one
     * @param SpecificationInterface $other
     */
    public function __construct(private SpecificationInterface $one, private SpecificationInterface $other)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function isSatisfiedBy($expectedValue, $actualValue = null): bool
    {
        return $this->one->isSatisfiedBy($expectedValue) && $this->other->isSatisfiedBy($expectedValue);
    }
}
