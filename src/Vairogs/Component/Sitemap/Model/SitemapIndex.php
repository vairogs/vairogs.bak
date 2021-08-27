<?php declare(strict_types = 1);

namespace Vairogs\Component\Sitemap\Model;

use DateTime;
use DateTimeInterface;

class SitemapIndex
{
    protected string $loc;
    protected ?DateTime $lastmod = null;

    public function getLoc(): string
    {
        return $this->loc;
    }

    public function setLoc(string $loc): static
    {
        $this->loc = $loc;

        return $this;
    }

    public function getLastmod(): ?DateTime
    {
        return $this->lastmod;
    }

    public function setLastmod(?DateTimeInterface $lastmod): static
    {
        $this->lastmod = $lastmod;

        return $this;
    }
}
