<?php declare(strict_types = 1);

namespace Vairogs\Extra\Constants;

final class Symbol
{
    final public const DIGITS = '0123456789';
    final public const EN_LOWERCASE = 'abcdefghijklmnopqrstuvwxyz';
    final public const EN_UPPERCASE = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    final public const SYMBOLS = '!@#$%^&*()_-=+;:.,?';
    final public const LV_LOWERCASE = 'aābcčdeēfgģhiījkķlļmnņoprsštuūvzž';
    final public const LV_UPPERCASE = 'AāBCČDEĒFGĢHIĪJKĶLĻMNŅOPRSŠTUŪVZŽ';
    final public const BASIC = self::EN_LOWERCASE . self::EN_UPPERCASE . self::DIGITS;
    final public const EXTENDED = self::BASIC . self::SYMBOLS;
}
