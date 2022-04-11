<?php declare(strict_types = 1);

namespace Vairogs\Core\Handler;

abstract class AbstractHandler implements Handler
{
    private ?Handler $nextHandler = null;

    public function setNext(Handler $handler): Handler
    {
        $this->nextHandler = $handler;

        return $this;
    }

    public function handle(...$arguments): mixed
    {
        return $this->nextHandler?->handle(...$arguments);
    }
}
