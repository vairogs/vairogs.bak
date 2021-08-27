<?php declare(strict_types = 1);

namespace Vairogs\Component\Sitemap\Builder;

interface Builder
{
    public function start(&$buffer): void;

    public function end(&$buffer): void;

    public function build(&$buffer): void;

    public function getType(): string;
}
