<?php declare(strict_types = 1);

namespace Vairogs\Utils\Handler;

use Vairogs\Utils\Helper\Php;

class FunctionHandler extends AbstractHandler
{
    private ?object $instance;
    private string $functionName;

    public function setFunction(string $functionName, ?object $instance = null): static
    {
        $this->instance = $instance;
        $this->functionName = $functionName;

        return $this;
    }

    /** @noinspection StaticClosureCanBeUsedInspection */
    public function handle(...$arguments): mixed
    {
        $function = $this->functionName;

        if (null === $this->instance) {
            return $function(...$arguments);
        }

        $object = $this->instance;

        return Php::call(fn () => $object->{$function}(...$arguments), $object, true, ...$arguments) ?? parent::handle(...$arguments);
    }
}
