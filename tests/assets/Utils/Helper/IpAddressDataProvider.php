<?php declare(strict_types = 1);

namespace Vairogs\Assets\Utils\Helper;

class IpAddressDataProvider
{
    public function dataProviderGetCIDRRange(): array
    {
        return [
            ['10.0.0.0/24', false, ['10.0.0.0', '10.0.0.255', ], ],
            ['10.0.0.0/24', true, [167772160, 167772415, ], ],
            ['vairogs', true, ['0', '0', ], ],
        ];
    }

    public function dataProviderGetRemoteIpCF(): array
    {
        return [
            ['86.204.153.254', '192.244.147.46', true, '192.244.147.46', ],
            ['86.204.153.254', null, true, '86.204.153.254', ],
            ['86.204.153.254', null, false, '127.0.0.1', ],
        ];
    }
}
