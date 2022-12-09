<?php declare(strict_types = 1);

namespace Vairogs\Functions\Tests\DataProvider;

use Vairogs\Functions\Tests\Assets\Model\Entity1;
use Vairogs\Functions\Tests\Assets\Model\Entity2;
use Vairogs\Functions\Tests\Assets\Model\Entity3;
use Vairogs\Functions\Tests\Assets\Model\Entity4;
use Vairogs\Functions\Tests\Assets\Model\Entity5;
use Vairogs\Functions\Tests\Assets\Model\Entity6;

class SortLatvianDataProvider
{
    public static function dataProviderSortLatvian(): array
    {
        $entity1 = (new Entity1())->setName(name: $n1 = 'zāle');
        $entity1::setTitle(title: $n1);
        $entity2 = (new Entity2())->setName(name: $n2 = 'āzis');
        $entity2::setTitle(title: $n2);
        $entity3 = (new Entity3())->setName(name: $n3 = 'zaķis');
        $entity3::setTitle(title: $n3);
        $entity4 = (new Entity4())->setName(name: $n4 = 'sala');
        $entity4::setTitle(title: $n4);
        $entity5 = (new Entity5())->setName(name: $n5 = 'ķēms');
        $entity5::setTitle(title: $n5);
        $entity6 = (new Entity6())->setName(name: $n6 = '');
        $entity6::setTitle(title: $n6);

        return [
            [[[$n1, ], [$n2, ], [$n3, ], [$n4, ], [$n5, ], ], 0, [[$n2, ], [$n5, ], [$n4, ], [$n3, ], [$n1, ], ], ],
            [[[$n1, ], [$n2, ], [$n3, ], [$n4, ], [$n5, ], ], 1, [[$n1, ], [$n2, ], [$n3, ], [$n4, ], [$n5, ], ], ],
            [[$entity1, $entity2, $entity3, $entity4, $entity5, ], 'name', [$entity2, $entity5, $entity4, $entity3, $entity1, ], ],
            [[$entity1, $entity2, $entity3, $entity4, $entity5, ], 'test', [$entity1, $entity2, $entity3, $entity4, $entity5, ], ],
            [[$entity1, $entity2, $entity3, $entity4, $entity5, ], 'title', [$entity2, $entity5, $entity4, $entity3, $entity1, ], ],
            [[[$n6, ], [$n1, ], ], 0, [[$n6, ], [$n1], ], ],
        ];
    }
}
