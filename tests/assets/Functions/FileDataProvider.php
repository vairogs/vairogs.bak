<?php declare(strict_types = 1);

namespace Vairogs\Tests\Assets\Functions;

class FileDataProvider
{
    public static function dataProviderHumanFileSize(): array
    {
        return [
            [8, 2, '8.00B', ],
            [1024, 0, '1K', ],
            [1024 * 1024, 3, '1.000M', ],
        ];
    }
}
