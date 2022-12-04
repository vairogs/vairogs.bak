<?php declare(strict_types = 1);

namespace Vairogs\Functions;

use Composer\InstalledVersions;

use function class_exists;
use function getenv;
use function interface_exists;
use function phpversion;
use function trait_exists;

use const PHP_VERSION_ID;

final class Composer
{
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

    public function exists(string $class): bool
    {
        return class_exists(class: $class) || interface_exists(interface: $class) || trait_exists(trait: $class);
    }

    public function getenv(string $name, bool $localOnly = true): mixed
    {
        return getenv($name, local_only: $localOnly) ?: ($_ENV[$name] ?? $name);
    }

    public function checkPhpVersion(int $phpVersionId): bool
    {
        return PHP_VERSION_ID >= $phpVersionId;
    }
}
