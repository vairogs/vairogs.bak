<?php declare(strict_types = 1);

namespace Vairogs\Sitemap\Model;

use JetBrains\PhpStorm\Pure;

use function method_exists;

class Sitemap
{
    /**
     * @var Url[]
     */
    protected array $urls = [];

    public function addUrl(Url $url): static
    {
        $this->urls[] = $url;

        return $this;
    }

    #[Pure]
    public function hasImages(): bool
    {
        foreach ($this->urls as $url) {
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

    public function setUrls(array $urls): static
    {
        $this->urls = $urls;

        return $this;
    }

    #[Pure]
    public function hasVideos(): bool
    {
        foreach ($this->urls as $url) {
            if ($url->hasVideos()) {
                return true;
            }
        }

        return false;
    }

    #[Pure]
    public function hasAlternates(): bool
    {
        foreach ($this->urls as $url) {
            if (method_exists(object_or_class: $url, method: 'hasAlternates') && $url->hasAlternates()) {
                return true;
            }
        }

        return false;
    }
}
