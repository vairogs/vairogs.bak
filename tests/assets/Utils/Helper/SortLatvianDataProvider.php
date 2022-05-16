<?php declare(strict_types = 1);

namespace Vairogs\Assets\Utils\Helper;

use Vairogs\Assets\Utils\Helper\Model\Entity1;
use Vairogs\Assets\Utils\Helper\Model\Entity2;
use Vairogs\Assets\Utils\Helper\Model\Entity3;
use Vairogs\Assets\Utils\Helper\Model\Entity4;
use Vairogs\Assets\Utils\Helper\Model\Entity5;
use Vairogs\Assets\Utils\Helper\Model\Entity6;

class SortLatvianDataProvider
{
    public function dataProviderSortLatvian(): array
    {
        $std1 = (new Entity1())->setName(name: $n1 = 'zāle');
        $std1::setTitle(title: $n1);
        $std2 = (new Entity2())->setName(name: $n2 = 'āzis');
        $std2::setTitle(title: $n2);
        $std3 = (new Entity3())->setName(name: $n3 = 'zaķis');
        $std3::setTitle(title: $n3);
        $std4 = (new Entity4())->setName(name: $n4 = 'sala');
        $std4::setTitle(title: $n4);
        $std5 = (new Entity5())->setName(name: $n5 = 'ķēms');
        $std5::setTitle(title: $n5);
        $std6 = (new Entity6())->setName(name: $n6 = '');
        $std6::setTitle(title: $n6);

        return [
            [[[$n1, ], [$n2, ], [$n3, ], [$n4, ], [$n5, ], ], 0, [[$n2, ], [$n5, ], [$n4, ], [$n3, ], [$n1, ], ], ],
            [[[$n1, ], [$n2, ], [$n3, ], [$n4, ], [$n5, ], ], 1, [[$n1, ], [$n2, ], [$n3, ], [$n4, ], [$n5, ], ], ],
            [[$std1, $std2, $std3, $std4, $std5, ], 'name', [$std2, $std5, $std4, $std3, $std1, ], ],
            [[$std1, $std2, $std3, $std4, $std5, ], 'test', [$std1, $std2, $std3, $std4, $std5, ], ],
            [[$std1, $std2, $std3, $std4, $std5, ], 'title', [$std2, $std5, $std4, $std3, $std1, ], ],
            [[[$n6, ], [$n1, ], ], 0, [[$n6, ], [$n1], ], ],
        ];
    }
}
