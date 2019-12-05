<?php declare(strict_types = 1);

namespace Vairogs\Component\Sitemap\Builder;

interface Builder
{
    /**
     * @param mixed $buffer
     */
    public function start(&$buffer): void;

    /**
     * @param mixed $buffer
     */
    public function end(&$buffer): void;

    /**
     * @param mixed $buffer
     */
    public function build(&$buffer): void;

    /**
     * @return string
     */
    public function getType(): string;
}
