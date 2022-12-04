<?php declare(strict_types = 1);

namespace Vairogs\Auth\OpenIDConnect\Utils\Constants\Enum;

use Vairogs\Functions\Traits\Cases;

enum Redirect: string
{
    use Cases;

    case ROUTE = 'route';
    case URI = 'uri';
}
