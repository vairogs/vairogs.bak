<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\Router;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use function in_array;

final class LocaleResolver
{
    use LocaleResolverTrait;

    private string $defaultLocale;
    private ?Request $request;
    private array $availableLocales;

    /**
     * @param string $defaultLocale
     * @param array $availableLocales
     */
    public function __construct(string $defaultLocale, array $availableLocales = [])
    {
        $this->defaultLocale = $defaultLocale;
        $this->availableLocales = $availableLocales;
    }

    public function resolve()
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
