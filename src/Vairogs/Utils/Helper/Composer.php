<?php declare(strict_types = 1);

namespace Vairogs\Utils\Helper;

use Composer\InstalledVersions;
use Vairogs\Utils\Twig\Attribute;
use function class_exists;
use function interface_exists;
use function phpversion;
use function str_replace;
use function str_starts_with;
use function trait_exists;

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

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function exists(string $class, bool $checkTrait = false): bool
    {
        $exists = class_exists(class: $class) || interface_exists(interface: $class);

        if (!$checkTrait) {
            return $exists;
        }

        return $exists || trait_exists(trait: $class);
    }
}
