<?php declare(strict_types = 1);

namespace Vairogs\Utils\Helper;

use Composer\InstalledVersions;
use Vairogs\Utils\Twig\Attribute;
use function phpversion;
use function str_replace;
use function str_starts_with;

class Composer
{
    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function isInstalled(array $packages, bool $includeDevRequirements = true): bool
    {
        foreach ($packages as $package) {
            if (str_starts_with(haystack: $package, needle: $prefix = 'ext-')) {
                if (false === phpversion(extension: str_replace(search: $prefix, replace: '', subject: $package))) {
                    return false;
                }

                continue;
            }

            if (!InstalledVersions::isInstalled(packageName: $package, includeDevRequirements: $includeDevRequirements)) {
                return false;
            }
        }

        return true;
    }
}
