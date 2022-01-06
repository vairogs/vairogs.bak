<?php declare(strict_types = 1);

namespace Vairogs\Translatable\Translation\Attribute;

use Attribute;
use function implode;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Translatable
{
    public function __construct(private array $classes = [], private ?string $type = null)
    {
    }

    public function getClasses(): string
    {
        return trim(implode(' ', $this->classes));
    }

    public function getType(): ?string
    {
        return $this->type;
    }
}
