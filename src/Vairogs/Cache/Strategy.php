<?php declare(strict_types = 1);

namespace Vairogs\Cache;

use Symfony\Component\HttpFoundation\Request;

final class Strategy
{
    final public const GET = Request::METHOD_GET;
    final public const POST = Request::METHOD_POST;
    final public const USER = 'USER';
    final public const MIXED = 'MIXED';
    final public const ALL = 'ALL';
}
