<?php declare(strict_types = 1);

namespace Vairogs\Sitemap\Model;

use DateTimeInterface;

class SitemapIndex
{
    protected ?DateTimeInterface $lastmod = null;
    protected string $loc;

    public function getLoc(): string
    {
        return $this->loc;
    }

    public function setLoc(string $loc): static
    {
        $this->loc = $loc;

        return $this;
    }

    public function getLastmod(): ?DateTimeInterface
    {
        return $this->lastmod;
    }

    public function setLastmod(?DateTimeInterface $lastmod): static
    {
        $this->lastmod = $lastmod;

        return $this;
    }
}
