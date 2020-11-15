<?php declare(strict_types = 1);

namespace Vairogs\Extra\Specification;

abstract class CompositeSpecification implements SpecificationInterface
{
    /**
     * {@inheritdoc}
     */
    public function andX(SpecificationInterface $specification): SpecificationInterface
    {
        return new AndSpecification($this, $specification);
    }

    /**
     * {@inheritdoc}
     */
    public function not(): SpecificationInterface
    {
        return new NotSpecification($this);
    }

    /**
     * {@inheritdoc}
     */
    public function orX(SpecificationInterface $specification): SpecificationInterface
    {
        return new OrSpecification($this, $specification);
    }
}
