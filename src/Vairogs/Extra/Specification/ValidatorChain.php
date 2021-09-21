<?php declare(strict_types = 1);

namespace Vairogs\Extra\Specification;

abstract class ValidatorChain
{
    /**
     * @var AbstractSpecification[]
     */
    protected array $validators = [];
    protected array $messages = [];

    public function setValidators(array $validators): static
    {
        $this->validators = $validators;

        return $this;
    }

    public function addValidator(AbstractSpecification $validator): static
    {
        $this->validators[$validator->getName()] = $validator;

        return $this;
    }

    abstract public function validate(array $data, mixed $object): bool;
}
