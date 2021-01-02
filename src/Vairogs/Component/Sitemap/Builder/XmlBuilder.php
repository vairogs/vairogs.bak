<?php declare(strict_types = 1);

namespace Vairogs\Component\Sitemap\Builder;

use Vairogs\Component\Sitemap\Model\RichUrl;
use Vairogs\Component\Sitemap\Model\Sitemap;
use function array_keys;
use function method_exists;
use function sprintf;
use function ucfirst;

class XmlBuilder implements Builder
{
    /**
     * @param Sitemap $sitemap
     */
    public function __construct(protected Sitemap $sitemap)
    {
    }

    /**
     * @param mixed $buffer
     */
    public function build(&$buffer): void
    {
        foreach ($this->sitemap->getUrls() as $url) {
            $alternates = [];
            $urlArray = $url->toArray();
            if ($url instanceof RichUrl) {
                $alternates = $url->getAlternateUrls();
                unset($urlArray['alternateUrl']);
            }
            $buffer .= '<url>
';
            foreach (array_keys($urlArray) as $key) {
                if (method_exists($url, $getter = 'get' . ucfirst($key)) && !empty($url->$getter())) {
                    $buffer .= "\t" . sprintf('<%s>', $key) . $url->$getter() . sprintf('</%s>', $key) . "\n";
                }
            }
            foreach ($alternates ?? [] as $locale => $alternate) {
                $buffer .= "\t" . '<xhtml:link rel="alternate" hreflang="' . $locale . '" href="' . $alternate . '" />' . "\n";
            }
            $buffer .= '</url>
';
        }
    }

    /**
     * @param mixed $buffer
     */
    public function end(&$buffer): void
    {
        $buffer .= '</urlset>
<!-- created with sitemap library for Symfony vairogs/sitemap -->';
    }

    /**
     * @param mixed $buffer
     */
    public function start(&$buffer): void
    {
        // @formatter:off
        $buffer .= '<?xml version="1.0" encoding="UTF-8"?>' .
            "\n" . '<urlset ' .
            "\n\t" . 'xmlns="https://www.sitemaps.org/schemas/sitemap/0.9" ' .
            "\n\t" . 'xmlns:xsi="https://www.w3.org/2001/XMLSchema-instance" ' .
            "\n\t" . 'xsi:schemaLocation="https://www.sitemaps.org/schemas/sitemap/0.9 https://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"';
        // @formatter:on
        if ($this->sitemap->hasAlternates()) {
            $buffer .= '
	xmlns:xhtml="http://www.w3.org/1999/xhtml" ';
        }
        if ($this->sitemap->hasVideos()) {
            $buffer .= '
	xmlns:video="http://www.google.com/schemas/sitemap-video/1.1"';
        }
        if ($this->sitemap->hasImages()) {
            $buffer .= '
	xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"';
        }
        $buffer .= '>
';
    }

    public function getType(): string
    {
        return 'string';
    }
}
