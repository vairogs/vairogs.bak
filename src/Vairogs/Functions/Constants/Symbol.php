<?php declare(strict_types = 1);

namespace Vairogs\Functions\Constants;

final class Symbol
{
    public const BASIC = self::EN_LOWERCASE . self::EN_UPPERCASE . self::DIGITS;
    public const DIGITS = '0123456789';
    public const EN_LOWERCASE = 'abcdefghijklmnopqrstuvwxyz';
    public const EN_UPPERCASE = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    public const EXTENDED = self::BASIC . self::SYMBOLS;
    public const LV_LOWERCASE = 'aābcčdeēfgģhiījkķlļmnņoprsštuūvzž';
    public const LV_UPPERCASE = 'AāBCČDEĒFGĢHIĪJKĶLĻMNŅOPRSŠTUŪVZŽ';
    public const SYMBOLS = '!@#$%^&*()_-=+;:.,?';
    public const UTF32LE = 'UTF-32LE';
    public const UTF8 = 'UTF-8';
}
