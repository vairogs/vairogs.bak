<?php declare(strict_types = 1);

namespace Vairogs\Functions\Handler;

abstract class AbstractHandler implements Handler
{
    private ?Handler $handler = null;

    public function next(Handler $handler): Handler
    {
        $this->handler = $handler;

        return $this;
    }

    public function handle(...$arguments): mixed
    {
        return $this->handler?->handle(...$arguments);
    }
}
