<?php declare(strict_types = 1);

namespace Vairogs\Tests\Source\Extra\Constants\Enum;

use Vairogs\Extra\Constants\Enum\Traits\Cases;

enum TestEnum
{
    use Cases;

    case ONE;
    case TWO;
}
