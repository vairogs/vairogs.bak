<?php declare(strict_types = 1);

namespace Vairogs\Utils;

use Random\Engine\Xoshiro256StarStar;
use Random\Randomizer;

abstract class AbstractHelper
{
    public function __construct(protected ?Randomizer $randomizer = null)
    {
        $this->randomizer ??= new Randomizer(new Xoshiro256StarStar());
    }
}
