<?php declare(strict_types = 1);

namespace Vairogs\Utils\Helper;

use JetBrains\PhpStorm\Pure;
use Vairogs\Utils\Twig\Attribute;
use function explode;
use function filter_var;
use function ip2long;
use const FILTER_FLAG_NO_PRIV_RANGE;
use const FILTER_FLAG_NO_RES_RANGE;
use const FILTER_FLAG_NONE;
use const FILTER_UNSAFE_RAW;
use const FILTER_VALIDATE_EMAIL;
use const FILTER_VALIDATE_IP;

final class Validate
{
    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    #[Pure]
    public static function validateEmail(string $email): bool
    {
        return false !== filter_var(value: filter_var(value: $email, filter: FILTER_UNSAFE_RAW), filter: FILTER_VALIDATE_EMAIL);
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    #[Pure]
    public static function validateIP(string $ip, bool $deny = true): bool
    {
        return false !== filter_var(value: $ip, filter: FILTER_VALIDATE_IP, options: $deny ? FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE : FILTER_FLAG_NONE);
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function validateCIDR(string $cidr): bool
    {
        if (!Http::isCIDR(cidr: $cidr)) {
            return false;
        }

        return Util::getCIDRRange(cidr: $cidr)[0] === ip2long(ip: explode(separator: '/', string: $cidr, limit: 2)[0]);
    }
}
