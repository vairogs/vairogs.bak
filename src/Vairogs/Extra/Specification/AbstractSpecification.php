<?php declare(strict_types = 1);

namespace Vairogs\Extra\Specification;

use const false;

abstract class AbstractSpecification extends CompositeSpecification
{
    protected string $name;
    protected string $message;
    protected bool $required;

    public function __construct(string $name, bool $required = false)
    {
        $this->name = $name;
        $this->required = $required;
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
