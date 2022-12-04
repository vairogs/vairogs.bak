<?php declare(strict_types = 1);

namespace Vairogs\Functions;

use JsonException;

use function defined;
use function function_exists;
use function json_decode;
use function json_encode;
use function json_validate;

use const JSON_BIGINT_AS_STRING;
use const JSON_PRESERVE_ZERO_FRACTION;
use const JSON_PRETTY_PRINT;
use const JSON_THROW_ON_ERROR;
use const JSON_UNESCAPED_SLASHES;
use const JSON_UNESCAPED_UNICODE;

final class Json
{
    public const FORCE_ARRAY = 0b0001;
    public const PRETTY = 0b0010;
    public const ASSOCIATIVE = 1;
    public const OBJECT = 0;

    /**
     * @throws JsonException
     */
    public function encode(mixed $value, int $flags = self::OBJECT): string
    {
        $flags = (JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | (self::OBJECT !== ($flags & self::PRETTY) ? JSON_PRETTY_PRINT : self::OBJECT) | (defined(constant_name: 'JSON_PRESERVE_ZERO_FRACTION') ? JSON_PRESERVE_ZERO_FRACTION : self::OBJECT));

        $json = json_encode(value: $value, flags: $flags | JSON_THROW_ON_ERROR);

        if (function_exists(function: 'json_validate')) {
            json_validate(json: $json);
        }

        return $json;
    }

    /**
     * @throws JsonException
     */
    public function decode(string $json, int $flags = self::OBJECT): mixed
    {
        if (function_exists(function: 'json_validate')) {
            json_validate(json: $json);
        }

        return json_decode(json: $json, associative: (bool) ($flags & self::FORCE_ARRAY), depth: JSON_THROW_ON_ERROR | JSON_BIGINT_AS_STRING, flags: $flags | JSON_THROW_ON_ERROR);
    }
}
