<?php declare(strict_types = 1);

namespace Vairogs\Sitemap\Model;

class Image
{
    protected string $loc;
    protected ?string $caption = null;
    protected ?string $geoLocation = null;
    protected ?string $title = null;
    protected ?string $license = null;

    public function getLoc(): string
    {
        return $this->loc;
    }

    public function setLoc(string $loc): static
    {
        $this->loc = $loc;

        return $this;
    }

    public function getCaption(): ?string
    {
        return $this->caption;
    }

    public function setCaption(?string $caption): static
    {
        $this->caption = $caption;

        return $this;
    }

    public function getGeoLocation(): ?string
    {
        return $this->geoLocation;
    }

    public function setGeoLocation(?string $geoLocation): static
    {
        $this->geoLocation = $geoLocation;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getLicense(): ?string
    {
        return $this->license;
    }

    public function setLicense(?string $license): static
    {
        $this->license = $license;

        return $this;
    }
}
