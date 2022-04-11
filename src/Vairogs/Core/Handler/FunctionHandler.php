<?php declare(strict_types = 1);

namespace Vairogs\Core\Handler;

use Vairogs\Utils\Helper\Php;

class FunctionHandler extends AbstractHandler
{
    private ?object $object;
    private string $function;

    public function setFunction(string $function, ?object $object = null): static
    {
        $this->object = $object;
        $this->function = $function;

        return $this;
    }

    public function handle(...$arguments): mixed
    {
        if (null === $this->object) {
            return ${$this->function}(...$arguments);
        }

        return Php::call(fn () => $this->object->{$this->function}(...$arguments), $this->object, true, ...$arguments) ?? parent::handle(...$arguments);
    }
}
