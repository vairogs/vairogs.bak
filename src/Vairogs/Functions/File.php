<?php declare(strict_types = 1);

namespace Vairogs\Functions;

use UnexpectedValueException;

use function array_map;
use function floor;
use function getcwd;
use function glob;
use function is_dir;
use function is_file;
use function mkdir;
use function rmdir;
use function sprintf;
use function strlen;
use function unlink;

use const DIRECTORY_SEPARATOR;
use const GLOB_NOSORT;

final class File
{
    /** @noinspection MkdirRaceConditionInspection */
    public function mkdir(string $dir): bool
    {
        if (!is_dir(filename: $dir)) {
            @mkdir(directory: $dir, recursive: true);
            if (!is_dir(filename: $dir)) {
                throw new UnexpectedValueException(message: sprintf('Directory "%s" was not created', $dir));
            }
        }

        return is_dir(filename: $dir);
    }

    public function fileExistsCwd(string $filename): bool
    {
        return is_file(filename: getcwd() . DIRECTORY_SEPARATOR . $filename);
    }

    public function humanFileSize(int $bytes, int $decimals = 2): string
    {
        $units = ['B', 'K', 'M', 'G', 'T', 'P', 'E', 'Z', 'Y'];
        $bytesAsString = (string) $bytes;
        $factor = (int) floor(num: (strlen(string: $bytesAsString) - 1) / 3);

        return sprintf("%.{$decimals}f", $bytesAsString / (1024 ** $factor)) . $units[$factor];
    }

    public function rmdir(string $directory): bool
    {
        array_map(callback: fn (string $file) => is_dir(filename: $file) ? $this->rmdir(directory: $file) : unlink(filename: $file), array: glob(pattern: $directory . '/*', flags: GLOB_NOSORT));

        return !is_dir(filename: $directory) || rmdir(directory: $directory);
    }
}
