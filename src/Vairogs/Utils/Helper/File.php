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
        return is_file(filename: getcwd() . '/' . $filename);
    }
}
