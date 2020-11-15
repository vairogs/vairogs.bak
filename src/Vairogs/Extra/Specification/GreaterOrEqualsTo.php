<?php declare(strict_types = 1);

namespace Vairogs\Extra\Specification;

use function sprintf;
use const false;
use const null;
use const true;

class GreaterOrEqualsTo extends AbstractSpecification
{
    public function isSatisfiedBy($expectedValue, $actualValue = null): bool
    {
        if ($actualValue >= $expectedValue) {
            return true;
        }
        $this->message = sprintf('%s is invalid as it is not greater than expected %s', $actualValue, $expectedValue);

        return false;
    }
}
