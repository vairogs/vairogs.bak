<?php declare(strict_types = 1);

namespace Vairogs\Extra\Constants;

final class Http
{
    final public const HTTP = 80;
    final public const HTTPS = 443;

    final public const HEADER_HTTPS = 'HTTPS';
    final public const HEADER_PORT = 'SERVER_PORT';
    final public const HEADER_PROTO = 'HTTP_X_FORWARDED_PROTO';
    final public const HEADER_SSL = 'HTTP_X_FORWARDED_SSL';
    final public const HTTP_CF_CONNECTING_IP = 'HTTP_CF_CONNECTING_IP';
    final public const HTTP_CLIENT_IP = 'HTTP_CLIENT_IP';
    final public const HTTP_X_FORWARDED_FOR = 'HTTP_X_FORWARDED_FOR';
    final public const HTTP_X_REAL_IP = 'HTTP_X_REAL_IP';
    final public const REMOTE_ADDR = 'REMOTE_ADDR';

    final public const SCHEMA_HTTP = 'http://';
    final public const SCHEMA_HTTPS = 'https://';
}
