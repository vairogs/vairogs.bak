<?php declare(strict_types = 1);

namespace Vairogs\Component\Sitemap\Builder;

use Vairogs\Extra\Constants\Type\Basic;

class XmlBuilder extends AbstractBuilder
{
    public function getType(): string
    {
        return Basic::STRING;
    }

    protected function write(&$buffer, string $text): void
    {
        $buffer .= $text;
    }
}
