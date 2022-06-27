<?php declare(strict_types = 1);

namespace Vairogs\Sitemap\Builder;

use Symfony\Component\PropertyInfo\Type;

use function fwrite;

class FileBuilder extends AbstractBuilder
{
    public function getType(): string
    {
        return Type::BUILTIN_TYPE_RESOURCE;
    }

    /** @noinspection PhpParameterByRefIsNotUsedAsReferenceInspection */
    protected function write(&$buffer, string $text): void
    {
        fwrite(stream: $buffer, data: $text);
    }
}
