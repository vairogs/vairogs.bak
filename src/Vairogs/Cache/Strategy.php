<?php declare(strict_types = 1);

namespace Vairogs\Cache;

use Symfony\Component\HttpFoundation\Request;

final class Strategy
{
    public const ALL = 'ALL';
    public const GET = Request::METHOD_GET;
    public const MIXED = 'MIXED';
    public const POST = Request::METHOD_POST;
    public const USER = 'USER';
}
