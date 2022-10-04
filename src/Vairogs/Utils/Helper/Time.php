<?php declare(strict_types = 1);

namespace Vairogs\Utils\Helper;

use Symfony\Component\PropertyInfo\Type;
use Vairogs\Extra\Constants;
use Vairogs\Twig\Attribute\TwigFilter;
use Vairogs\Twig\Attribute\TwigFunction;

use function floor;
use function round;
use function trim;

final class Time
{
    public const TIME = [
        'hour' => Constants\Time::HOUR,
        'minute' => Constants\Time::MIN,
        'second' => Constants\Time::SEC,
        'micro' => Constants\Time::MS,
    ];

    #[TwigFunction]
    #[TwigFilter]
    public function format(int|float $timestamp, bool $asArray = false): array|string
    {
        $timestamp = round(num: $timestamp * 1000);
        $result = $asArray ? [] : '';

        foreach (self::TIME as $unit => $value) {
            if ($timestamp >= $value) {
                $time = (int) floor(num: $timestamp / $value);
                if ($time > 0) {
                    match (get_debug_type(value: $result)) {
                        Type::BUILTIN_TYPE_STRING => $result .= $time . ' ' . $unit . (1 === $time ? '' : 's') . ' ',
                        Type::BUILTIN_TYPE_ARRAY => $result[$unit] = $time,
                    };
                }

                $timestamp -= $time * $value;
            }
        }

        return match (get_debug_type(value: $result)) {
            Type::BUILTIN_TYPE_STRING => trim(string: $result),
            Type::BUILTIN_TYPE_ARRAY => $result,
        };
    }
}
