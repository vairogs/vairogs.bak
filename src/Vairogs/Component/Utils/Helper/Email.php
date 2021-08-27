<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\Helper;

use JetBrains\PhpStorm\Pure;
use Vairogs\Component\Utils\Twig\Annotation;
use function filter_var;

class Email
{
    /**
     * @Annotation\TwigFunction()
     */
    #[Pure]
    public static function isValid(string $email): bool
    {
        if (empty($email)) {
            return false;
        }

        $email = filter_var($email, FILTER_SANITIZE_STRING);

        return false !== filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}
