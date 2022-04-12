<?php declare(strict_types = 1);

namespace Vairogs\Utils\Handler;

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
        $function = $this->function;

        if (null === $this->object) {
            return $function(...$arguments);
        }

        $object = $this->object;

        return Php::call(fn () => $object->{$function}(...$arguments), $object, true, ...$arguments) ?? parent::handle(...$arguments);
    }
}
