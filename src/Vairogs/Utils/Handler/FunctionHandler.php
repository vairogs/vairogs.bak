<?php declare(strict_types = 1);

namespace Vairogs\Utils\Handler;

use Vairogs\Utils\Helper\Closure;

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

    public function handle(...$arguments): mixed
    {
        return (new Closure())->hijackCall($this->instance, $this->functionName, true, ...$arguments) ?? parent::handle(...$arguments);
    }
}
