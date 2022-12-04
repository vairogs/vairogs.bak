<?php declare(strict_types = 1);

namespace Vairogs\Sitemap\Builder;

use Vairogs\Functions\Php;
use Vairogs\Sitemap\Model\RichUrl;
use Vairogs\Sitemap\Model\Sitemap;
use Vairogs\Sitemap\Model\Url;

use function array_keys;
use function method_exists;
use function sprintf;

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
                unset($urlArray['alternateUrls']);
            }

            $this->write(buffer: $buffer, text: '<url>' . "\n");

            $this->writeUrls(buffer: $buffer, url: $url, urlArray: $urlArray);
            $this->writeAlternates(buffer: $buffer, alternates: $alternates);

            $this->write(buffer: $buffer, text: '</url>' . "\n");
        }
    }

    public function end(&$buffer): void
    {
        $this->write(buffer: $buffer, text: '</urlset>' . "\n" . '<!-- created with sitemap library for Symfony vairogs/sitemap -->');
    }

    /** @noinspection HttpUrlsUsage */
    public function start(&$buffer): void
    {
        $this->write(
            buffer: $buffer,
            text: '<?xml version="1.0" encoding="UTF-8"?>' .
            "\n" .
            '<urlset ' .
            "\n\t" .
            'xmlns="https://www.sitemaps.org/schemas/sitemap/0.9" ' .
            "\n\t" .
            'xmlns:xsi="https://www.w3.org/2001/XMLSchema-instance" ' .
            "\n\t" .
            'xsi:schemaLocation="https://www.sitemaps.org/schemas/sitemap/0.9 https://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"',
        );

        if ($this->sitemap->hasAlternates()) {
            $this->write(buffer: $buffer, text: "\n\t" . 'xmlns:xhtml="http://www.w3.org/1999/xhtml" ');
        }

        if ($this->sitemap->hasVideos()) {
            $this->write(buffer: $buffer, text: "\n\t" . 'xmlns:video="http://www.google.com/schemas/sitemap-video/1.1"');
        }

        if ($this->sitemap->hasImages()) {
            $this->write(buffer: $buffer, text: "\n\t" . 'xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"');
        }

        $this->write(buffer: $buffer, text: '>' . "\n");
    }

    abstract protected function write(&$buffer, string $text): void;

    protected function getBufferValue(Url $url, string $key): string
    {
        if ($getter = $this->getGetterValue(url: $url, key: $key)) {
            return "\t" . sprintf('<%s>', $key) . $getter . sprintf('</%s>', $key) . "\n";
        }

        return '';
    }

    protected function getGetterValue(Url $url, string $key): ?string
    {
        if (method_exists(object_or_class: $url, method: $getter = (new Php())->getter(variable: $key)) && !empty($url->{$getter}())) {
            return (string) $url->{$getter}();
        }

        return null;
    }

    private function writeAlternates(&$buffer, array $alternates = []): void
    {
        foreach ($alternates as $locale => $alternate) {
            $this->write(buffer: $buffer, text: "\t" . '<xhtml:link rel="alternate" hreflang="' . $locale . '" href="' . $alternate . '" />' . "\n");
        }
    }

    private function writeUrls(&$buffer, Url|RichUrl $url, array $urlArray = []): void
    {
        foreach (array_keys(array: $urlArray) as $key) {
            $this->write(buffer: $buffer, text: $this->getBufferValue(url: $url, key: $key));
        }
    }
}
