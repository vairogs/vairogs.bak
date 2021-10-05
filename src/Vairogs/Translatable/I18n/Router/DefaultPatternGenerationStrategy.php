<?php declare(strict_types = 1);

namespace Vairogs\Translatable\I18n\Router;

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorBagInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class DefaultPatternGenerationStrategy implements PatternGenerationStrategyInterface
{
    public const STRATEGY_PREFIX = 'prefix';
    public const STRATEGY_PREFIX_EXCEPT_DEFAULT = 'prefix_except_default';
    public const STRATEGY_CUSTOM = 'custom';

    public function __construct(private string $strategy, private TranslatorInterface $translator, private array $locales, private string $cacheDir, private string $translationDomain = 'routes', private string $defaultLocale = 'en')
    {
    }

    public function generateI18nPatterns(string $routeName, Route $route): array
    {
        $patterns = [];
        foreach ($route->getOption(name: 'i18n_locales') ?: $this->locales as $locale) {
            if ($this->translator instanceof TranslatorBagInterface) {
                if (!$this->translator->getCatalogue(locale: $locale)->has(id: $routeName, domain: $this->translationDomain)) {
                    $i18nPattern = $route->getPath();
                } else {
                    $i18nPattern = $this->translator->trans(id: $routeName, parameters: [], domain: $this->translationDomain, locale: $locale);
                }
            } elseif ($routeName === $i18nPattern = $this->translator->trans(id: $routeName, parameters: [], domain: $this->translationDomain, locale: $locale)) {
                $i18nPattern = $route->getPath();
            }

            if (self::STRATEGY_PREFIX === $this->strategy
                || (self::STRATEGY_PREFIX_EXCEPT_DEFAULT === $this->strategy && $this->defaultLocale !== $locale)) {
                $i18nPattern = '/' . $locale . $i18nPattern;
                if (null !== $route->getOption(name: 'i18n_prefix')) {
                    $i18nPattern = $route->getOption(name: 'i18n_prefix') . $i18nPattern;
                }
            }

            $patterns[$i18nPattern][] = $locale;
        }

        return $patterns;
    }

    public function addResources(RouteCollection $i18NRouteRouteCollection): void
    {
        foreach ($this->locales as $locale) {
            if (is_file(filename: $metadata = $this->cacheDir . '/translations/catalogue.' . $locale . '.php.meta')) {
                foreach (unserialize(data: file_get_contents(filename: $metadata), options: ['allowed_classes' => RouterInterface::class]) as $resource) {
                    $i18NRouteRouteCollection->addResource(resource: $resource);
                }
            }
        }
    }
}
