<?php declare(strict_types = 1);

namespace Vairogs\Utils\Handler;

interface Handler
{
    public function setNext(self $handler): self;

    public function handle(...$arguments): mixed;
}
