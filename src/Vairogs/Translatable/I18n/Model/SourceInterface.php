<?php declare(strict_types = 1);

namespace Vairogs\Translatable\I18n\Model;

use Stringable;

interface SourceInterface extends Stringable
{
    public function equals(self $source): bool;
}
