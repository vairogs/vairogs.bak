<?php declare(strict_types = 1);

namespace Vairogs\Translation\Annotation;

use Attribute;
use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation
 * @Annotation\Target({Target::TARGET_CLASS})
 */
#[Attribute(Attribute::TARGET_CLASS)]
class TranslatableEntity
{
}
