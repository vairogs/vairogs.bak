<?php declare(strict_types = 1);

namespace Vairogs\Utils\Router;

use Symfony\Component\HttpFoundation\Request;
use Vairogs\Utils\Handler\FunctionHandler;
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
    protected array $availableLocales = [];

    public function resolveLocale(Request $request, array $availableLocales = []): ?string
    {
        if (!empty($this->hostMap[$request->getHost()])) {
            return $this->hostMap[$request->getHost()];
        }
        $this->availableLocales = $availableLocales;

        $byLang = (new FunctionHandler())->setFunction(function: 'byLang', object: $this);
        $byCookie = (new FunctionHandler())->setFunction(function: 'byCookie', object: $this)->setNext(handler: $byLang);
        $bySession = (new FunctionHandler())->setFunction(function: 'bySession', object: $this)->setNext(handler: $byCookie);
        $byParameter = (new FunctionHandler())->setFunction(function: 'byParameter', object: $this)->setNext(handler: $bySession);

        return $byParameter->handle(request: $request);
    }

    public function byParameter(Request $request): ?string
    {
        foreach (['hl', 'lang'] as $parameter) {
            if ($request->query->has(key: $parameter) && $result = $this->pregMatch(hostLanguage: $request->query->get(key: $parameter))) {
                return $result;
            }
        }

        return null;
    }

    public function bySession(Request $request): ?string
    {
        if ($request->hasPreviousSession()) {
            $session = $request->getSession();

            if ($session->isStarted() && $session->has(name: '_locale')) {
                return $session->get(name: '_locale');
            }
        }

        return null;
    }

    public function byCookie(Request $request): ?string
    {
        if ($request->cookies->has(key: $this->cookieName) && $result = $this->pregMatch(hostLanguage: $request->cookies->get(key: $this->cookieName))) {
            return $result;
        }

        return null;
    }

    public function byLang(Request $request): ?string
    {
        foreach ($this->parseLanguages(request: $request) as $lang) {
            if (in_array(needle: $lang, haystack: $this->availableLocales, strict: true)) {
                return $lang;
            }
        }

        return null;
    }

    private function pregMatch($hostLanguage): ?string
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
                continue;
            }

            $languages[] = $language;
        }

        return array_unique(array: $languages);
    }
}
