<?php declare(strict_types = 1);

namespace Vairogs\Extra\Specification;

use JetBrains\PhpStorm\Pure;

abstract class CompositeSpecification implements SpecificationInterface
{
    /**
     * {@inheritdoc}
     */
    #[Pure] public function andX(SpecificationInterface $specification): SpecificationInterface
    {
        return new AndSpecification($this, $specification);
    }

    /**
     * {@inheritdoc}
     */
    #[Pure] public function not(): SpecificationInterface
    {
        return new NotSpecification($this);
    }

    /**
     * {@inheritdoc}
     */
    #[Pure] public function orX(SpecificationInterface $specification): SpecificationInterface
    {
        return new OrSpecification($this, $specification);
    }
}
