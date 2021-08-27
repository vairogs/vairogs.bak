<?php declare(strict_types = 1);

namespace Vairogs\Component\Sitemap\Model;

use JetBrains\PhpStorm\Pure;
use function method_exists;

class Sitemap
{
    protected array $urls = [];

    public function addUrl(Url $url): Sitemap
    {
        $this->urls[] = $url;

        return $this;
    }

    #[Pure]
    public function hasImages(): bool
    {
        foreach ($this->getUrls() as $url) {
            if ($url->hasImages()) {
                return true;
            }
        }

        return false;
    }

    public function getUrls(): array
    {
        return $this->urls;
    }

    public function setUrls(array $urls): Sitemap
    {
        $this->urls = $urls;

        return $this;
    }

    #[Pure]
    public function hasVideos(): bool
    {
        foreach ($this->getUrls() as $url) {
            if ($url->hasVideos()) {
                return true;
            }
        }

        return false;
    }

    public function hasAlternates(): bool
    {
        foreach ($this->getUrls() as $url) {
            if (method_exists($url, 'hasAlternates') && $url->hasAlternates()) {
                return true;
            }
        }

        return false;
    }
}
