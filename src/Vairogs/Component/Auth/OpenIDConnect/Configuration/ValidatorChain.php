<?php declare(strict_types = 1);

namespace Vairogs\Component\Auth\OpenIDConnect\Configuration;

use Exception;
use Lcobucci\JWT\Validation\Constraint;
use Vairogs\Extra\Specification\ValidatorChain as BaseValidatorChain;

class ValidatorChain extends BaseValidatorChain
{
    protected array $assertions = [];

    public function validate(array $data, mixed $object): bool
    {
        $valid = true;

        /**
         * @var string     $claim
         * @var Constraint $assertion
         */
        foreach ($this->assertions as $claim => $assertion) {
            try {
                $assertion->assert(token: $object);
            } catch (Exception $e) {
                $valid = false;
                $this->messages[$claim] = $e->getMessage();
            }
        }

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

    public function setAssertions(array $assertions): self
    {
        $this->assertions = $assertions;

        return $this;
    }
}
