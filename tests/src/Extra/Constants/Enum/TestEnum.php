<?php declare(strict_types = 1);

namespace Vairogs\Tests\Source\Extra\Constants\Enum;

use Vairogs\Extra\Constants\Enum\Traits\CasesTrait;

enum TestEnum
{
    use CasesTrait;

    case ONE;
    case TWO;
}
