<?php declare(strict_types = 1);

namespace Vairogs\Component\Sitemap\Builder;

interface Builder
{
    /**
     * @param mixed $buffer
     */
    public function start(mixed &$buffer): void;

    /**
     * @param mixed $buffer
     */
    public function end(mixed &$buffer): void;

    /**
     * @param mixed $buffer
     */
    public function build(mixed &$buffer): void;

    /**
     * @return string
     */
    public function getType(): string;
}
