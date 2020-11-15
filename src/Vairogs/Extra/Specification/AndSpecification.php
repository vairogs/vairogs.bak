<?php declare(strict_types = 1);

namespace Vairogs\Extra\Specification;

class AndSpecification extends CompositeSpecification
{
    private SpecificationInterface $one;
    private SpecificationInterface $other;

    /**
     * @param SpecificationInterface $one
     * @param SpecificationInterface $other
     */
    public function __construct(SpecificationInterface $one, SpecificationInterface $other)
    {
        $this->one = $one;
        $this->other = $other;
    }

    /**
     * {@inheritdoc}
     */
    public function isSatisfiedBy($expectedValue, $actualValue = null): bool
    {
        return $this->one->isSatisfiedBy($expectedValue) && $this->other->isSatisfiedBy($expectedValue);
    }
}
