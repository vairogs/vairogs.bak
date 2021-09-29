<?php declare(strict_types = 1);

namespace Vairogs\Cache\Utils;

use Symfony\Component\HttpFoundation\Request;

final class Strategy
{
    public const GET = Request::METHOD_GET;
    public const POST = Request::METHOD_POST;
    public const USER = 'USER';
    public const MIXED = 'MIXED';
    public const ALL = 'ALL';
}
