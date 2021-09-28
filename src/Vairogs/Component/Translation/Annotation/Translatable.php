<?php declare(strict_types = 1);

namespace Vairogs\Component\Translation\Annotation;

use Attribute;
use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\NamedArgumentConstructor;
use Doctrine\Common\Annotations\Annotation\Target;
use function implode;

/**
 * @Annotation
 * @Annotation\Target({Target::TARGET_PROPERTY})
 * @NamedArgumentConstructor()
 */
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
