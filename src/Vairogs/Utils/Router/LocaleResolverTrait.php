<?php declare(strict_types = 1);

namespace Vairogs\Utils\Router;

use Symfony\Component\HttpFoundation\Request;
use function array_unique;
use function explode;
use function in_array;
use function preg_match;
use function reset;
use function strlen;

trait LocaleResolverTrait
{
    protected string $cookieName;
    protected array $hostMap;
    protected Request $request;

    public function resolveLocale(Request $request, array $availableLocales): mixed
    {
        if (!empty($this->hostMap[$request->getHost()])) {
            return $this->hostMap[$request->getHost()];
        }

        $functions = [
            'returnByQueryParameter',
            'returnByPreviousSession',
            'returnByCookie',
            'returnByLang',
        ];

        foreach ($functions as $function) {
            if ($result = $this->{$function}($request, $availableLocales)) {
                return $result;
            }
        }

        return null;
    }

    protected function returnByQueryParameter(Request $request): ?string
    {
        // @formatter:off
        foreach (['hl', 'lang'] as $parameter) {
            // @formatter:on

            if ($request->query->has(key: $parameter) && $result = $this->preg(hostLanguage: $request->query->get(key: $parameter))) {
                return $result;
            }
        }

        return null;
    }

    protected function returnByPreviousSession(Request $request): ?string
    {
        if ($request->hasPreviousSession()) {
            $session = $request->getSession();

            if ($session && $session->has(name: '_locale')) {
                return $session->get(name: '_locale');
            }
        }

        return null;
    }

    protected function returnByCookie(Request $request): ?string
    {
        if ($request->cookies->has(key: $this->cookieName) && $result = $this->preg(hostLanguage: $request->cookies->get(key: $this->cookieName))) {
            return $result;
        }

        return null;
    }

    protected function returnByLang(Request $request, array $availableLocales): ?string
    {
        foreach ($this->parseLanguages(request: $request) as $lang) {
            if (in_array(needle: $lang, haystack: $availableLocales, strict: true)) {
                return $lang;
            }
        }

        return null;
    }

    private function preg($hostLanguage): ?string
    {
        if (preg_match(pattern: '#^[a-z]{2}(?:_[a-z]{2})?$#i', subject: $hostLanguage)) {
            return $hostLanguage;
        }

        return null;
    }

    private function parseLanguages(Request $request): array
    {
        $languages = [];
        foreach ($request->getLanguages() as $language) {
            if (2 !== strlen(string: $language)) {
                $newLang = explode(separator: '_', string: $language, limit: 2);
                $languages[] = reset(array: $newLang);
            } else {
                $languages[] = $language;
            }
        }

        return array_unique(array: $languages) ?? [];
    }
}
