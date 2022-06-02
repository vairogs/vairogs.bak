<?php declare(strict_types = 1);

namespace Vairogs\Auth\OpenIDConnect\Utils\Constants\Enum;

use Vairogs\Extra\Constants\Enum\Traits\CasesTrait;

enum Redirect: string
{
    use CasesTrait;

    case ROUTE = 'route';
    case URI = 'uri';
}
