<?php declare(strict_types = 1);

namespace Vairogs\Tests\Source\Functions\Constants\Enum;

use Vairogs\Functions\Traits\Cases;

enum TestEnum
{
    use Cases;

    case ONE;
    case TWO;
}
