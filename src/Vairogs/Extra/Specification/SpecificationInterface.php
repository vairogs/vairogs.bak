<?php declare(strict_types = 1);

namespace Vairogs\Extra\Specification;

interface SpecificationInterface
{
    /**
     * @param $expectedValue
     * @param $actualValue
     *
     * @return boolean
     */
    public function isSatisfiedBy($expectedValue, $actualValue = null): bool;

    /**
     * @param SpecificationInterface $specification
     *
     * @return SpecificationInterface
     */
    public function andX(SpecificationInterface $specification): SpecificationInterface;

    /**
     * @param SpecificationInterface $specification
     *
     * @return SpecificationInterface
     */
    public function orX(SpecificationInterface $specification): SpecificationInterface;

    /**
     * @return SpecificationInterface
     * @internal param SpecificationInterface $specification
     */
    public function not(): SpecificationInterface;
}
