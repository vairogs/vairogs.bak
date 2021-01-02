<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\Router;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use function in_array;

final class LocaleResolver
{
    use LocaleResolverTrait;

    private ?Request $request = null;

    /**
     * @param string $defaultLocale
     * @param array $availableLocales
     */
    public function __construct(private string $defaultLocale, private array $availableLocales = [])
    {
    }

    /**
     * @return string|null
     */
    public function resolve(): ?string
    {
        $locale = $this->resolveLocale($this->request, $this->availableLocales);
        if (in_array($locale, $this->availableLocales, true)) {
            return $locale;
        }

        return $this->defaultLocale;
    }

    /**
     * @param RequestStack $requestStack
     */
    public function setRequest(RequestStack $requestStack): void
    {
        $this->request = $requestStack->getCurrentRequest();
    }
}
