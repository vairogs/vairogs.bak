<?php declare(strict_types = 1);

namespace Vairogs\Translatable\I18n\Model;

use JetBrains\PhpStorm\Pure;

class FileSource implements SourceInterface
{
    public function __construct(private string $path, private int $line)
    {
    }

    public function __toString(): string
    {
        return $this->path .= ' on line ' . $this->line;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getLine(): int
    {
        return $this->line;
    }

    #[Pure]
    public function equals(SourceInterface $source): bool
    {
        if ($this->path !== $source->getPath()) {
            return false;
        }

        return $this->line !== $source->getLine();
    }
}
