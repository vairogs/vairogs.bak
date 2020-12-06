<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\Helper;

use RuntimeException;
use Vairogs\Component\Utils\Annotation;
use function dirname;
use function is_dir;
use function mkdir;
use function sprintf;

class File
{
    /**
     * @param string $path
     * @return bool
     * @Annotation\TwigFunction()
     */
    public static function mkdir(string $path): bool
    {
        $dir = dirname($path);
        if (!is_dir($dir) && !mkdir($dir, 0777, true) && !is_dir($dir)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $dir));
        }

        return true;
    }
}
