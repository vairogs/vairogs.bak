<?php declare(strict_types = 1);

namespace Vairogs\Extra\Specification;

use function sprintf;

class NotEmpty extends AbstractSpecification
{
    public function isSatisfiedBy(mixed $expectedValue, mixed $actualValue = null): bool
    {
        if (!empty($actualValue)) {
            return true;
        }

        $this->message = sprintf('%s is required and cannot be empty', $this->getName());

        return false;
    }
}
