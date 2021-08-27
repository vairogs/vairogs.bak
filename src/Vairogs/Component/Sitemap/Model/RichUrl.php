<?php declare(strict_types = 1);

namespace Vairogs\Component\Sitemap\Model;

use JetBrains\PhpStorm\Pure;

class RichUrl extends Url
{
    protected array $alternateUrl = [];

    public function addAlternateUrl(string $locale, string $url): Url
    {
        $this->alternateUrl[$locale] = $url;

        return $this;
    }

    #[Pure]
    public function hasAlternates(): bool
    {
        return !empty($this->getAlternateUrls());
    }

    public function getAlternateUrls(): array
    {
        return $this->alternateUrl;
    }
}
