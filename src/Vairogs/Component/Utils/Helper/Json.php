<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\Helper;

use JsonException;
use function defined;
use function json_decode;
use function json_encode;
use function json_last_error;
use function json_last_error_msg;
use const JSON_BIGINT_AS_STRING;
use const JSON_PRESERVE_ZERO_FRACTION;
use const JSON_PRETTY_PRINT;
use const JSON_THROW_ON_ERROR;
use const JSON_UNESCAPED_SLASHES;
use const JSON_UNESCAPED_UNICODE;

class Json
{
    /**
     * @var int
     */
    public const FORCE_ARRAY = 0b0001;

    /**
     * @var int
     */
    public const PRETTY = 0b0010;

    /**
     * @param $value
     * @param int $flags
     *
     * @return string
     * @throws JsonException
     */
    public static function encode($value, int $flags = 0): string
    {
        $flags = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | (($flags & self::PRETTY) ? JSON_PRETTY_PRINT : 0) | (defined('JSON_PRESERVE_ZERO_FRACTION') ? JSON_PRESERVE_ZERO_FRACTION : 0);
        $json = json_encode($value, $flags | JSON_THROW_ON_ERROR, 512);
        if ($error = json_last_error()) {
            throw new JsonException(json_last_error_msg(), $error);
        }

        return $json;
    }

    /**
     * @param string $json
     * @param int $flags
     *
     * @return mixed
     * @throws JsonException
     */
    public static function decode(string $json, int $flags = 0)
    {
        $forceArray = (bool)($flags & self::FORCE_ARRAY);
        $value = json_decode($json, $forceArray, 512, JSON_THROW_ON_ERROR | JSON_BIGINT_AS_STRING);
        if ($error = json_last_error()) {
            throw new JsonException(json_last_error_msg(), $error);
        }

        return $value;
    }
}
