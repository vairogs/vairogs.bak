<?php declare(strict_types = 1);

namespace Vairogs\Component\Auth\OpenIDConnect\Configuration;

use Exception;
use Lcobucci\JWT\Validation\Constraint;
use Vairogs\Component\Auth\OpenIDConnect\Exception\InvalidConstraintException;
use Vairogs\Extra\Specification\ValidatorChain as BaseValidatorChain;
use function sprintf;

class ValidatorChain extends BaseValidatorChain
{
    /**
     * @var Constraint[]
     */
    protected array $assertions = [];

    public function validate(array $data, mixed $object): bool
    {
        return $this->validateAssertions(object: $object) && $this->validateSpecifications(data: $data, object: $object);
    }

    public function setAssertions(array $assertions): self
    {
        $this->assertions = $assertions;

        return $this;
    }

    protected function validateAssertions(mixed $object): bool
    {
        $valid = true;

        foreach ($this->assertions as $claim => $assertion) {
            try {
                $assertion->assert(token: $object);
            } catch (InvalidConstraintException $e) {
                $this->messages[$claim] = $e->getMessage();
            } catch (Exception $e) {
                $valid = false;
                $this->messages[$claim] = $e->getMessage();
            }
        }

        return $valid;
    }

    protected function validateSpecifications(array $data, mixed $object): bool
    {
        $valid = true;

        foreach ($this->validators as $claim => $validator) {
            if (false === $object->hasClaim(claim: $claim)) {
                if ($validator->isRequired()) {
                    $valid = false;
                    $this->messages[$claim] = sprintf('Missing required value for claim %s', $claim);
                }
            } else {
                if (isset($data[$claim]) && !$validator->isSatisfiedBy(expectedValue: $data[$claim], actualValue: $object->getClaim(claim: $claim))) {
                    $valid = false;
                    $this->messages[$claim] = $validator->getMessage();
                }
            }
        }

        return $valid;
    }
}
