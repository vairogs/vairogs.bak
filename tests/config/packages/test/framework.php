<?php declare(strict_types = 1);

use Symfony\Config\FrameworkConfig;

return static function (FrameworkConfig $config): void {
    $config
        ->test(value: true)
        ->httpMethodOverride(value: true);
};
