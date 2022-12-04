<?php declare(strict_types = 1);

namespace Vairogs\Functions\Constants;

final class Http
{
    public const HTTP = 80;
    public const HTTPS = 443;

    public const HEADER_HTTPS = 'HTTPS';
    public const HEADER_PORT = 'SERVER_PORT';
    public const HEADER_PROTO = 'HTTP_X_FORWARDED_PROTO';
    public const HEADER_SSL = 'HTTP_X_FORWARDED_SSL';
    public const HTTP_CF_CONNECTING_IP = 'HTTP_CF_CONNECTING_IP';
    public const HTTP_CLIENT_IP = 'HTTP_CLIENT_IP';
    public const HTTP_X_FORWARDED_FOR = 'HTTP_X_FORWARDED_FOR';
    public const HTTP_X_REAL_IP = 'HTTP_X_REAL_IP';
    public const REMOTE_ADDR = 'REMOTE_ADDR';

    public const SCHEMA_HTTP = 'http://';
    public const SCHEMA_HTTPS = 'https://';
}
