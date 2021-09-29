<?php declare(strict_types = 1);

namespace Vairogs\Sitemap\Model;

use JetBrains\PhpStorm\Pure;

class RichUrl extends Url
{
    protected array $alternateUrls = [];

    public function addAlternateUrl(string $locale, string $url): static
    {
        $this->alternateUrls[$locale] = $url;

        return $this;
    }

    #[Pure]
    public function hasAlternates(): bool
    {
        return !empty($this->alternateUrls);
    }

    public function getAlternateUrls(): array
    {
        return $this->alternateUrls;
    }
}
