<?php declare(strict_types = 1);

namespace Vairogs\Functions\Handler;

use Vairogs\Functions\Closure;

use function is_object;

class FunctionHandler extends AbstractHandler
{
    public function __construct(private readonly string $function, private readonly ?object $instance = null)
    {
    }

    public function handle(...$arguments): mixed
    {
        if (!is_object(value: $this->instance)) {
            return (new Closure())->hijackReturn($this->function, ...$arguments) ?? parent::handle(...$arguments);
        }

        return (new Closure())->hijackReturnObject($this->instance, $this->function, ...$arguments) ?? parent::handle(...$arguments);
    }
}
