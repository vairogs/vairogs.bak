<?php declare(strict_types = 1);

namespace Vairogs\Component\Sitemap\Builder;

use function fwrite;

class FileBuilder extends AbstractBuilder
{
    public function getType(): string
    {
        return 'resource';
    }

    protected function write(&$buffer, string $text): void
    {
        fwrite(stream: $buffer, data: $text);
    }
}
