<?php declare(strict_types = 1);

namespace Vairogs\Component\Auth\OpenIDConnect\Configuration;

use Vairogs\Extra\Specification\AbstractSpecification;

class ValidatorChain
{
    /**
     * @var AbstractSpecification[]
     */
    protected array $validators = [];
    protected array $messages = [];

    public function setValidators(array $validators): static
    {
        $this->validators = [];

        foreach ($validators as $validator) {
            $this->addValidator(validator: $validator);
        }

        return $this;
    }

    public function addValidator(AbstractSpecification $validator): static
    {
        $this->validators[$validator->getName()] = $validator;

        return $this;
    }

    public function validate(array $data, IdToken $token): bool
    {
        $valid = true;
        foreach ($this->validators as $claim => $validator) {
            if (false === $token->hasClaim(claim: $claim)) {
                if ($validator->isRequired()) {
                    $valid = false;
                    $this->messages[$claim] = sprintf('Missing required value for claim %s', $claim);
                }
            } else {
                if (isset($data[$claim]) && !$validator->isSatisfiedBy(expectedValue: $data[$claim], actualValue: $token->getClaim(claim: $claim))) {
                    $valid = false;
                    $this->messages[$claim] = $validator->getMessage();
                }
            }
        }

        return $valid;
    }
}
