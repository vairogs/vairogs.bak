<?php declare(strict_types = 1);

namespace Vairogs\Utils\Helper;

use JetBrains\PhpStorm\Pure;
use Vairogs\Utils\Twig\Attribute;
use function filter_var;

final class Email
{
    #[Attribute\TwigFunction]
    #[Pure]
    public static function isValid(string $email): bool
    {
        if (empty($email)) {
            return false;
        }

        return false !== filter_var(value: filter_var(value: $email, filter: FILTER_UNSAFE_RAW), filter: FILTER_VALIDATE_EMAIL);
    }
}
