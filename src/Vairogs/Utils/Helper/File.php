<?php declare(strict_types = 1);

namespace Vairogs\Utils\Helper;

use JetBrains\PhpStorm\Pure;
use UnexpectedValueException;
use Vairogs\Utils\Twig\Annotation;
use function dirname;
use function getcwd;
use function is_dir;
use function is_file;
use function mkdir;
use function sprintf;

class File
{
    #[Annotation\TwigFunction]
    public static function mkdir(string $path): bool
    {
        $dir = dirname(path: $path);

        if (!is_dir(filename: $dir) && !mkdir(directory: $dir, recursive: true) && !is_dir(filename: $dir)) {
            throw new UnexpectedValueException(message: sprintf('Directory "%s" was not created', $dir));
        }

        return true;
    }

    #[Annotation\TwigFunction]
    #[Pure]
    public static function fileExistsPublic(string $filename): bool
    {
        return is_file(filename: getcwd() . '/' . $filename);
    }
}
