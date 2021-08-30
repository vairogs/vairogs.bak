<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\Helper;

use JetBrains\PhpStorm\Pure;
use RuntimeException;
use Vairogs\Component\Utils\Twig\Annotation;
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
        $dir = dirname($path);
        if (!is_dir($dir) && !mkdir($dir, 0777, true) && !is_dir($dir)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $dir));
        }

        return true;
    }

    #[Annotation\TwigFunction]
    #[Pure]
    public static function fileExistsPublic(string $filename): bool
    {
        return is_file(getcwd() . '/' . $filename);
    }
}
