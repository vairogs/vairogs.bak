<?php declare(strict_types = 1);

namespace Vairogs\Utils\Helper;

use Composer\InstalledVersions;
use Vairogs\Twig\Attribute;
use function class_exists;
use function getenv;
use function interface_exists;
use function phpversion;
use function str_replace;
use function str_starts_with;
use function trait_exists;

final class Composer
{
    /**
     * @psalm-param array<string> $packages
     */
    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function isInstalled(array $packages, bool $incDevReq = true): bool
    {
        foreach ($packages as $package) {
            if ($this->isExtensionInstalled(extension: $package)) {
                continue;
            }

            if (!InstalledVersions::isInstalled(packageName: $package, includeDevRequirements: $incDevReq)) {
                return false;
            }
        }

        return true;
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function isExtensionInstalled(string $extension): bool
    {
        if (str_starts_with(haystack: $extension, needle: $prefix = 'ext-')) {
            $extension = str_replace(search: $prefix, replace: '', subject: $extension);
        }

        return false !== phpversion(extension: $extension);
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function exists(string $class): bool
    {
        return class_exists(class: $class) || interface_exists(interface: $class) || trait_exists(trait: $class);
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function getenv(string $name, bool $localOnly = true): mixed
    {
        if ($env = getenv(name: $name, local_only: $localOnly)) {
            return $env;
        }

        return $_ENV[$name] ?? $name;
    }
}
