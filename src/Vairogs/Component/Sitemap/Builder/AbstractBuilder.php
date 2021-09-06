<?php declare(strict_types = 1);

namespace Vairogs\Component\Sitemap\Builder;

use Vairogs\Component\Sitemap\Model\RichUrl;
use Vairogs\Component\Sitemap\Model\Sitemap;
use Vairogs\Component\Sitemap\Model\Url;
use function array_keys;
use function method_exists;
use function sprintf;
use function ucfirst;

abstract class AbstractBuilder implements Builder
{
    public function __construct(protected Sitemap $sitemap)
    {
    }

    public function build(&$buffer): void
    {
        foreach ($this->sitemap->getUrls() as $url) {
            $alternates = [];
            $urlArray = $url->toArray();

            if ($url instanceof RichUrl) {
                $alternates = $url->getAlternateUrls();
                unset($urlArray['alternateUrl']);
            }

            $this->write($buffer, '<url>' . "\n");

            foreach (array_keys($urlArray) as $key) {
                $this->write($buffer, $this->getBufferValue($url, $key));
            }

            foreach ($alternates as $locale => $alternate) {
                $this->write($buffer, "\t" . '<xhtml:link rel="alternate" hreflang="' . $locale . '" href="' . $alternate . '" />' . "\n");
            }

            $this->write($buffer, '</url>' . "\n");
        }
    }

    abstract protected function write(&$buffer, string $text): void;

    protected function getBufferValue(Url $url, string $key): string
    {
        if ($getter = $this->getGetterValue($url, $key)) {
            return "\t" . sprintf('<%s>', $key) . $getter . sprintf('</%s>', $key) . "\n";
        }

        return '';
    }

    protected function getGetterValue(Url $url, string $key): ?string
    {
        if (method_exists($url, $getter = 'get' . ucfirst($key)) && !empty($url->$getter())) {
            return (string)$url->$getter();
        }

        return null;
    }

    public function end(&$buffer): void
    {
        $this->write($buffer, '</urlset>' . "\n" . '<!-- created with sitemap library for Symfony vairogs/sitemap -->');
    }

    public function start(&$buffer): void
    {
        // @formatter:on
        $this->write($buffer,
            '<?xml version="1.0" encoding="UTF-8"?>' .
            "\n" .
            '<urlset ' .
            "\n\t" .
            'xmlns="https://www.sitemaps.org/schemas/sitemap/0.9" ' .
            "\n\t" .
            'xmlns:xsi="https://www.w3.org/2001/XMLSchema-instance" ' .
            "\n\t" .
            'xsi:schemaLocation="https://www.sitemaps.org/schemas/sitemap/0.9 https://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"'
        );
        // @formatter:on

        if ($this->sitemap->hasAlternates()) {
            $this->write($buffer, "\n\t" . 'xmlns:xhtml="http://www.w3.org/1999/xhtml" ');
        }

        if ($this->sitemap->hasVideos()) {
            $this->write($buffer, "\n\t" . 'xmlns:video="http://www.google.com/schemas/sitemap-video/1.1"');
        }

        if ($this->sitemap->hasImages()) {
            $this->write($buffer, "\n\t" . 'xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"');
        }

        $this->write($buffer, '>' . "\n");
    }
}
