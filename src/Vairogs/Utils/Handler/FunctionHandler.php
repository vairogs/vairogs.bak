<?php declare(strict_types = 1);

namespace Vairogs\Utils\Handler;

use Vairogs\Utils\Helper\Closure;
use function is_object;

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
        if (!is_object(value: $this->instance)) {
            return (new Closure())->hijackReturn($this->functionName, ...$arguments) ?? parent::handle(...$arguments);
        }

        return (new Closure())->hijackReturnObject($this->instance, $this->functionName, ...$arguments) ?? parent::handle(...$arguments);
    }
}
