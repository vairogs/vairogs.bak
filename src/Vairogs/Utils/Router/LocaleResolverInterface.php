<?php declare(strict_types = 1);

namespace Vairogs\Utils\Router;

use Symfony\Component\HttpFoundation\Request;

interface LocaleResolverInterface
{
    public function resolveLocale(Request $request, array $availableLocales = []): ?string;
}
