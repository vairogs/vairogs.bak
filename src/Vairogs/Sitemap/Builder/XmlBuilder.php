<?php declare(strict_types = 1);

namespace Vairogs\Sitemap\Builder;

use Symfony\Component\PropertyInfo\Type;

class XmlBuilder extends AbstractBuilder
{
    public function getType(): string
    {
        return Type::BUILTIN_TYPE_STRING;
    }

    protected function write(&$buffer, string $text): void
    {
        $buffer .= $text;
    }
}
