<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\Router;

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

    /**
     * @param Request $request
     * @param array $availableLocales
     * @return mixed|null
     */
    public function resolveLocale(Request $request, array $availableLocales)
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

    /**
     * @param Request $request
     * @return string|null
     */
    protected function returnByQueryParameter(Request $request): ?string
    {
        // @formatter:off
        foreach (['hl', 'lang'] as $parameter) {
            // @formatter:on
            if ($request->query->has($parameter) && $result = $this->preg($request->query->get($parameter))) {
                return $result;
            }
        }

        return null;
    }

    /**
     * @param $hostLanguage
     * @return string|null
     */
    private function preg($hostLanguage): ?string
    {
        if (preg_match('#^[a-z]{2}(?:_[a-z]{2})?$#i', $hostLanguage)) {
            return $hostLanguage;
        }

        return null;
    }

    /**
     * @param Request $request
     * @return string|null
     */
    protected function returnByPreviousSession(Request $request): ?string
    {
        if ($request->hasPreviousSession()) {
            $session = $request->getSession();
            if ($session && $session->has('_locale')) {
                return $session->get('_locale');
            }
        }

        return null;
    }

    /**
     * @param Request $request
     * @return string|null
     */
    protected function returnByCookie(Request $request): ?string
    {
        if ($request->cookies->has($this->cookieName) && $result = $this->preg($request->cookies->get($this->cookieName))) {
            return $result;
        }

        return null;
    }

    /**
     * @param Request $request
     * @param array $availableLocales
     * @return string|null
     */
    protected function returnByLang(Request $request, array $availableLocales): ?string
    {
        foreach ($this->parseLanguages($request) as $lang) {
            if (in_array($lang, $availableLocales, true)) {
                return $lang;
            }
        }

        return null;
    }

    /**
     * @param Request $request
     * @return array
     */
    private function parseLanguages(Request $request): array
    {
        $languages = [];
        foreach ($request->getLanguages() as $language) {
            if (strlen($language) !== 2) {
                $newLang = explode('_', $language, 2);
                $languages[] = reset($newLang);
            } else {
                $languages[] = $language;
            }
        }

        return array_unique($languages) ?? [];
    }
}
