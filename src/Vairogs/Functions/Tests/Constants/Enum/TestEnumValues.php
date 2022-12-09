<?php declare(strict_types = 1);

namespace Vairogs\Functions\Tests\Constants\Enum;

use Vairogs\Functions\Traits\Cases;

enum TestEnumValues: string
{
    use Cases;

    case ONE = 'one';
    case TWO = 'two';
}
