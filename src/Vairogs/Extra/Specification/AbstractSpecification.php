<?php declare(strict_types = 1);

namespace Vairogs\Extra\Specification;

abstract class AbstractSpecification extends CompositeSpecification
{
    protected string $message;

    public function __construct(protected string $name, protected bool $required = false)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }
}
