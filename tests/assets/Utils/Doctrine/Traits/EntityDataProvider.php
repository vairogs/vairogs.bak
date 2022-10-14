<?php declare(strict_types = 1);

namespace Vairogs\Tests\Assets\Utils\Doctrine\Traits;

class EntityDataProvider
{
    public function dataProviderEntity(): array
    {
        return [
            [1, 1, 1, ],
            [22, 3, 0, ],
        ];
    }
}
