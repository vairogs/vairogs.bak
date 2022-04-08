<?php declare(strict_types = 1);

namespace Vairogs\Sitemap\Model;

use DateTimeInterface;
use JetBrains\PhpStorm\Pure;
use function get_object_vars;
use function number_format;

class Url
{
    protected array $videos = [];
    protected array $images = [];
    protected string $loc;
    protected ?DateTimeInterface $lastmod = null;
    protected ?string $changefreq = null;
    protected float $priority = 0.5;

    public function getLoc(): string
    {
        return $this->loc;
    }

    public function setLoc(string $loc): static
    {
        $this->loc = $loc;

        return $this;
    }

    public function getLastmod(): ?string
    {
        return $this->lastmod?->format(format: DateTimeInterface::ATOM);
    }

    public function setLastmod(?DateTimeInterface $lastmod): static
    {
        $this->lastmod = $lastmod;

        return $this;
    }

    public function getChangefreq(): ?string
    {
        return $this->changefreq;
    }

    public function setChangefreq(?string $changefreq): static
    {
        $this->changefreq = $changefreq;

        return $this;
    }

    #[Pure]
    public function getPriority(): string
    {
        return number_format(num: $this->priority, decimals: 2);
    }

    public function setPriority(float $priority): static
    {
        $this->priority = $priority;

        return $this;
    }

    public function addVideo(Video $video): static
    {
        $this->videos[] = $video;

        return $this;
    }

    public function addImage(Image $image): static
    {
        $this->images[] = $image;

        return $this;
    }

    #[Pure]
    public function toArray(): array
    {
        return get_object_vars(object: $this);
    }

    #[Pure]
    public function hasVideos(): bool
    {
        return !empty($this->videos);
    }

    public function getVideos(): array
    {
        return $this->videos;
    }

    public function setVideos(array $videos): static
    {
        $this->videos = $videos;

        return $this;
    }

    #[Pure]
    public function hasImages(): bool
    {
        return !empty($this->images);
    }

    public function getImages(): array
    {
        return $this->images;
    }

    public function setImages(array $images): static
    {
        $this->images = $images;

        return $this;
    }
}
