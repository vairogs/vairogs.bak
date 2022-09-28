<?php declare(strict_types = 1);

namespace Vairogs\Utils;

use Random\Engine\Secure;
use Random\Randomizer;

abstract class AbstractHelper
{
    public function __construct(protected ?Randomizer $randomizer = null)
    {
        $this->randomizer ??= new Randomizer(new Secure());
    }
}
