<?php declare(strict_types = 1);

namespace Vairogs\Extra\Specification;

use JetBrains\PhpStorm\Pure;

abstract class CompositeSpecification implements SpecificationInterface
{
    #[Pure]
    public function andX(SpecificationInterface $specification): SpecificationInterface
    {
        return new AndSpecification(one: $this, other: $specification);
    }

    #[Pure]
    public function not(): SpecificationInterface
    {
        return new NotSpecification(specification: $this);
    }

    #[Pure]
    public function orX(SpecificationInterface $specification): SpecificationInterface
    {
        return new OrSpecification(one: $this, other: $specification);
    }
}
