<?php declare(strict_types = 1);

namespace Vairogs\Utils\Helper;

use UnexpectedValueException;
use Vairogs\Utils\Twig\Attribute;
use function dirname;
use function getcwd;
use function is_dir;
use function is_file;
use function mkdir;
use function sprintf;
use function str_starts_with;
use const DIRECTORY_SEPARATOR;

final class File
{
    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function mkdir(string $path): bool
    {
        if (!is_dir(filename: $dir = dirname(path: $path)) && !mkdir(directory: $dir, recursive: true) && !is_dir(filename: $dir)) {
            throw new UnexpectedValueException(message: sprintf('Directory "%s" was not created', $dir));
        }

        return true;
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function fileExistsPublic(string $filename): bool
    {
        return is_file(filename: getcwd() . DIRECTORY_SEPARATOR . $filename);
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function detectLanguage(string $body): string
    {
        return match (true) {
            str_starts_with(haystack: $body, needle: '<?xml') => 'xml',
            str_starts_with(haystack: $body, needle: '{'), str_starts_with(haystack: $body, needle: '[') => 'json',
            default => 'plain',
        };
    }
}
