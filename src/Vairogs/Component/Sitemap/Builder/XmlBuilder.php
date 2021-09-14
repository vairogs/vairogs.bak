<?php declare(strict_types = 1);

namespace Vairogs\Component\Sitemap\Builder;

class XmlBuilder extends AbstractBuilder
{
    public function getType(): string
    {
        return 'string';
    }

    protected function write(&$buffer, string $text): void
    {
        $buffer .= $text;
    }
}
