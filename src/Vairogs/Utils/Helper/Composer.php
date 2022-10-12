<?php declare(strict_types = 1);

namespace Vairogs\Utils\Helper;

use Composer\InstalledVersions;
use Vairogs\Twig\Attribute\TwigFilter;
use Vairogs\Twig\Attribute\TwigFunction;

use function class_exists;
use function getenv;
use function interface_exists;
use function phpversion;
use function trait_exists;

final class Composer
{
    /**
     * @psalm-param array<string> $packages
     */
    #[TwigFunction]
    #[TwigFilter]
    public function isInstalled(array $packages, bool $incDevReq = false): bool
    {
        foreach ($packages as $package) {
            if (false !== phpversion(extension: $package)) {
                continue;
            }

            if (!InstalledVersions::isInstalled(packageName: $package, includeDevRequirements: $incDevReq)) {
                return false;
            }
        }

        return true;
    }

    #[TwigFunction]
    #[TwigFilter]
    public function exists(string $class): bool
    {
        return class_exists(class: $class) || interface_exists(interface: $class) || trait_exists(trait: $class);
    }

    #[TwigFunction]
    #[TwigFilter]
    public function getenv(string $name, bool $localOnly = true): mixed
    {
        return getenv($name, local_only: $localOnly) ?: ($_ENV[$name] ?? $name);
    }
}
