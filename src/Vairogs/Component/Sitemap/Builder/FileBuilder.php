<?php declare(strict_types = 1);

namespace Vairogs\Component\Sitemap\Builder;

use Vairogs\Extra\Constants\Type\Basic;
use function fwrite;

class FileBuilder extends AbstractBuilder
{
    public function getType(): string
    {
        return Basic::RESOURCE;
    }

    protected function write(&$buffer, string $text): void
    {
        fwrite($buffer, $text);
    }
}
