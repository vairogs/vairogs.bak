<?php declare(strict_types = 1);

namespace Vairogs\Functions;

use Random\Engine\Xoshiro256StarStar;
use Random\Randomizer;

use function array_rand;
use function str_shuffle;

abstract class Abstraction
{
    protected bool $is82;

    public function __construct()
    {
        $this->is82 = (new Composer())->checkPhpVersion(phpVersionId: 80200) && (new Composer())->isInstalled(packages: ['random']);
    }

    protected function shuffle(string $string): string
    {
        if ($this->is82) {
            return (new Randomizer(engine: new Xoshiro256StarStar()))->shuffleBytes(bytes: $string);
        }

        return str_shuffle(string: $string);
    }

    protected function pick(array $array): int|string|array
    {
        if ($this->is82) {
            return (new Randomizer(engine: new Xoshiro256StarStar()))->pickArrayKeys(array: $array, num: 1)[0];
        }

        return array_rand(array: $array);
    }
}
