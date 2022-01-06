<?php declare(strict_types = 1);

namespace Vairogs\Utils\Helper;

use JsonException;
use Vairogs\Utils\Twig\Attribute;
use function defined;
use function json_decode;
use function json_encode;
use function json_last_error;
use function json_last_error_msg;

final class Json
{
    public const FORCE_ARRAY = 0b0001;
    public const PRETTY = 0b0010;

    /**
     * @throws JsonException
     */
    #[Attribute\TwigFilter]
    public static function encode(mixed $value, int $flags = 0): string
    {
        $flags = (JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | ((0 !== ($flags & self::PRETTY)) ? JSON_PRETTY_PRINT : 0) | (defined(constant_name: 'JSON_PRESERVE_ZERO_FRACTION') ? JSON_PRESERVE_ZERO_FRACTION : 0));
        $json = json_encode(value: $value, flags: $flags | JSON_THROW_ON_ERROR);

        if (0 !== ($error = json_last_error())) {
            throw new JsonException(message: json_last_error_msg(), previous: $error);
        }

        return $json;
    }

    /**
     * @throws JsonException
     */
    #[Attribute\TwigFilter]
    public static function decode(string $json, int $flags = 0): mixed
    {
        $forceArray = (bool) ($flags & self::FORCE_ARRAY);
        /** @noinspection PhpRedundantOptionalArgumentInspection */
        $value = json_decode(json: $json, associative: $forceArray, depth: 512, flags: JSON_THROW_ON_ERROR | JSON_BIGINT_AS_STRING);

        if (0 !== ($error = json_last_error())) {
            throw new JsonException(message: json_last_error_msg(), previous: $error);
        }

        return $value;
    }
}
