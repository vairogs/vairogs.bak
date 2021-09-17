<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\Router;

use Symfony\Component\HttpFoundation\RequestStack;
use function in_array;

final class LocaleResolver
{
    use LocaleResolverTrait;

    public function __construct(private string $defaultLocale, private array $availableLocales = [])
    {
    }

    public function resolve(): ?string
    {
        $locale = $this->resolveLocale(request: $this->request, availableLocales: $this->availableLocales);

        if (in_array(needle: $locale, haystack: $this->availableLocales, strict: true)) {
            return $locale;
        }

        return $this->defaultLocale;
    }

    public function setRequest(RequestStack $requestStack): void
    {
        $this->request = $requestStack->getCurrentRequest();
    }
}
