<?php declare(strict_types = 1);

namespace Vairogs\Translatable\I18n\Router;

use Exception;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Matcher\RequestMatcherInterface;
use Vairogs\Translatable\I18n\Exception\NotAcceptableLanguageException;
use Vairogs\Utils\Router\LocaleResolverInterface;

class I18nRouter extends Router
{
    private array $hostMap = [];
    private string $i18nLoaderId;
    private bool $redirectToHost = true;
    private LocaleResolverInterface $localeResolver;
    private ContainerInterface $container;

    /**
     * @noinspection MagicMethodsValidityInspection
     * @noinspection PhpMissingParentConstructorInspection
     */
    public function __construct()
    {
        call_user_func_array(callback: [Router::class, '__construct'], args: func_get_args());
        $this->container = func_get_arg(position: 0);
    }

    public function setLocaleResolver(LocaleResolverInterface $resolver): static
    {
        $this->localeResolver = $resolver;

        return $this;
    }

    public function setRedirectToHost(bool $bool): void
    {
        $this->redirectToHost = $bool;
    }

    public function setHostMap(array $hostMap): static
    {
        $this->hostMap = $hostMap;

        return $this;
    }

    public function setI18nLoaderId(string $id): static
    {
        $this->i18nLoaderId = $id;

        return $this;
    }

    public function setDefaultLocale(string $locale): static
    {
        $this->defaultLocale = $locale;

        return $this;
    }

    public function generate($name, $parameters = [], $referenceType = self::ABSOLUTE_PATH): string
    {
        $currentLocale = $this->context->getParameter(name: '_locale');
        if (isset($parameters['_locale'])) {
            $locale = $parameters['_locale'];
        } elseif ($currentLocale) {
            $locale = $currentLocale;
        } else {
            $locale = $this->defaultLocale;
        }

        if ($currentLocale && $currentLocale !== $locale && $this->hostMap) {
            $referenceType = self::NETWORK_PATH === $referenceType ? self::NETWORK_PATH : self::ABSOLUTE_URL;
        }
        $needsHost = (self::NETWORK_PATH === $referenceType || self::ABSOLUTE_URL === $referenceType) && $this->hostMap;

        /** @var UrlGeneratorInterface $generator */
        $generator = $this->getGenerator();

        $currentHost = $this->context->getHost();
        if ($needsHost) {
            $this->context->setHost(host: $this->hostMap[$locale]);
        }

        try {
            $url = $generator->generate(name: $locale . I18nLoader::ROUTING_PREFIX . $name, parameters: $parameters, referenceType: $referenceType);

            if ($needsHost) {
                $this->context->setHost(host: $currentHost);
            }

            return $url;
        } catch (Exception) {
            if ($needsHost) {
                $this->context->setHost(host: $currentHost);
            }
        }

        return $generator->generate(name: $name, parameters: $parameters, referenceType: $referenceType);
    }

    public function match(string $pathinfo): array
    {
        return $this->matchI18n(params: parent::match(pathinfo: $pathinfo), url: $pathinfo);
    }

    public function getRouteCollection()
    {
        return $this->container->get(id: $this->i18nLoaderId)?->load(collection: parent::getRouteCollection());
    }

    public function getOriginalRouteCollection(): array
    {
        return parent::getRouteCollection();
    }

    public function matchRequest(Request $request): array
    {
        $matcher = $this->getMatcher();
        $pathInfo = $request->getPathInfo();
        if (!$matcher instanceof RequestMatcherInterface) {
            /* @noinspection PhpNamedArgumentMightBeUnresolvedInspection */
            return $this->matchI18n(params: $matcher->match(pathinfo: $pathInfo), url: $pathInfo);
        }

        return $this->matchI18n(params: $matcher->matchRequest(request: $request), url: $pathInfo);
    }

    private function matchI18n(array $params, string $url): array
    {
        $request = $this->getRequest();

        $currentLocale = null;
        if (isset($params['_locales'])) {
            if (false !== $pos = strpos(haystack: $params['_route'], needle: I18nLoader::ROUTING_PREFIX)) {
                $params['_route'] = substr(string: $params['_route'], offset: $pos + strlen(string: I18nLoader::ROUTING_PREFIX));
            }

            if (null !== $request && !($currentLocale = $this->context->getParameter(name: '_locale'))) {
                $currentLocale = $this->localeResolver->resolveLocale(request: $request, availableLocales: $params['_locales']);

                if (!$currentLocale) {
                    $currentLocale = reset(array: $params['_locales']);
                }
            }

            if (!in_array(needle: $currentLocale, haystack: $params['_locales'], strict: true)) {
                if ($this->hostMap) {
                    $hostMap = $this->hostMap;
                    $availableHosts = array_map(static fn ($locale) => $hostMap[$locale], $params['_locales']);

                    $differentHost = true;
                    foreach ($availableHosts as $host) {
                        if ($this->hostMap[$currentLocale] === $host) {
                            $differentHost = false;
                            break;
                        }
                    }

                    if ($differentHost) {
                        throw new ResourceNotFoundException(message: sprintf('The route "%s" is not available on the current host "%s", but only on these hosts "%s".', $params['_route'], $this->hostMap[$currentLocale], implode(separator: ', ', array: $availableHosts)));
                    }
                }

                throw new NotAcceptableLanguageException(requestedLanguage: $currentLocale, availableLanguages: $params['_locales']);
            }

            unset($params['_locales']);
            $params['_locale'] = $currentLocale;
        } elseif (isset($params['_locale']) && 0 < $pos = strpos(haystack: $params['_route'], needle: I18nLoader::ROUTING_PREFIX)) {
            $params['_route'] = substr(string: $params['_route'], offset: $pos + strlen(string: I18nLoader::ROUTING_PREFIX));
        }

        // check if the matched route belongs to a different locale on another host
        if (isset($params['_locale'], $this->hostMap[$params['_locale']]) && $this->context->getHost() !== $host = $this->hostMap[$params['_locale']]) {
            if (!$this->redirectToHost) {
                throw new ResourceNotFoundException(message: sprintf('Resource corresponding to pattern "%s" not found for locale "%s".', $url, $this->getContext()->getParameter(name: '_locale')));
            }

            return [
                '_controller' => 'Vairogs\Translatable\I18n\Controller\RedirectController::redirectAction',
                'path' => $url,
                'host' => $host,
                'permanent' => true,
                'scheme' => $this->context->getScheme(),
                'httpPort' => $this->context->getHttpPort(),
                'httpsPort' => $this->context->getHttpsPort(),
                '_route' => $params['_route'],
            ];
        }

        if (!isset($params['_locale']) && null !== $request && $locale = $this->localeResolver->resolveLocale(request: $request, availableLocales: $this->container->getParameter(name: 'vairogs.translatable.i18n.locales'))) {
            $params['_locale'] = $locale;
        }

        return $params;
    }

    private function getRequest(): ?Request
    {
        $request = null;
        if ($this->container->has(id: 'request_stack')) {
            $request = $this->container->get(id: 'request_stack')?->getCurrentRequest();
        } elseif (method_exists(object_or_class: $this->container, method: 'isScopeActive') && $this->container->isScopeActive('request')) {
            $request = $this->container->get(id: 'request');
        }

        return $request;
    }
}
