<?php declare(strict_types = 1);

namespace Vairogs\Assets\Utils\Helper;

use Vairogs\Assets\Utils\Doctrine\Traits\Entity;

class SortLatvianDataProvider
{
    public function dataProviderSortLatvian(): array
    {
        [$std1 ,$std2 ,$std3 ,$std4, $std5] = [
            (new Entity())->setName(name: $n1 = 'zāle'),
            (new Entity())->setName(name: $n2 = 'āzis'),
            (new Entity())->setName(name: $n3 = 'zaķis'),
            (new Entity())->setName(name: $n4 = 'sala'),
            (new Entity())->setName(name: $n5 = 'ķēms'),
        ];

        return [
            [[[$n1], [$n2], [$n3], [$n4], [$n5]], 0, [[$n2], [$n5], [$n4], [$n3], [$n1]]],
            [[[$n1], [$n2], [$n3], [$n4], [$n5]], 1, [[$n1], [$n2], [$n3], [$n4], [$n5]]],
            [[$std1, $std2, $std3, $std4, $std5], 'name', [$std2, $std5, $std4, $std3, $std1]],
            [[$std1, $std2, $std3, $std4, $std5], 'test', [$std1, $std2, $std3, $std4, $std5]],
        ];
    }
}
