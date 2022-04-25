<?php declare(strict_types = 1);

namespace Vairogs\Utils\Helper;

use UnexpectedValueException;
use Vairogs\Utils\Twig\Attribute;
use function dirname;
use function floor;
use function getcwd;
use function is_dir;
use function is_file;
use function mkdir;
use function sprintf;
use function strlen;
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
    public static function fileExistsPublic(string $filename, ?string $directory = null): bool
    {
        return is_file(filename: ($directory ?? getcwd()) . DIRECTORY_SEPARATOR . $filename);
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function humanFileSize(string $bytes, int $decimals = 2): string
    {
        $sz = 'BKMGTP';
        $factor = (int) floor(num: (strlen(string: $bytes) - 1) / 3);

        return sprintf("%.{$decimals}f", $bytes / (1024 ** $factor)) . @$sz[$factor];
    }
}
