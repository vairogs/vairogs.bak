<?php declare(strict_types = 1);

namespace Vairogs\Assets\Utils\Helper;

use Vairogs\Assets\Utils\Doctrine\Traits\Entity;

class SortLatvianDataProvider
{
    public function dataProviderSortLatvian(): array
    {
        [$std1 ,$std2 ,$std3 ,$std4, $std5] = [
            (new Entity())->setName(name: 'zāle'),
            (new Entity())->setName(name: 'āzis'),
            (new Entity())->setName(name: 'zaķis'),
            (new Entity())->setName(name: 'sala'),
            (new Entity())->setName(name: 'ķēms'),
        ];

        return [
            [[['zāle'], ['āzis'], ['zaķis'], ['sala'], ['ķēms']], 0, [['āzis'], ['ķēms'], ['sala'], ['zaķis'], ['zāle']]],
            [[['zāle'], ['āzis'], ['zaķis'], ['sala'], ['ķēms']], 1, [['zāle'], ['āzis'], ['zaķis'], ['sala'], ['ķēms']]],
            [[$std1, $std2, $std3, $std4, $std5], 'name', [$std2, $std5, $std4, $std3, $std1]],
            [[$std1, $std2, $std3, $std4, $std5], 'test', [$std1, $std2, $std3, $std4, $std5]],
        ];
    }
}
