<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\Helper;

use function filter_var;
use const false;
use const FILTER_SANITIZE_STRING;
use const FILTER_VALIDATE_EMAIL;

class Email
{
    /**
     * @param $email
     *
     * @return bool
     */
    public static function isValid($email): bool
    {
        if (empty($email)) {
            return false;
        }

        $email = filter_var($email, FILTER_SANITIZE_STRING);

        return false !== filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}
