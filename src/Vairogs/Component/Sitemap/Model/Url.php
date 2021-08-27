<?php declare(strict_types = 1);

namespace Vairogs\Component\Sitemap\Model;

use DateTime;
use DateTimeInterface;
use JetBrains\PhpStorm\Pure;
use function get_object_vars;
use function number_format;

class Url
{
    protected array $videos = [];
    protected array $images = [];
    protected string $loc;
    protected ?DateTime $lastmod = null;
    protected ?string $changefreq = null;
    protected float $priority = 0.5;

    public function getLoc(): string
    {
        return $this->loc;
    }

    public function setLoc(string $loc): self
    {
        $this->loc = $loc;

        return $this;
    }

    public function getLastmod(): ?string
    {
        return $this->lastmod?->format(DateTimeInterface::ATOM);
    }

    public function setLastmod(?DateTimeInterface $lastmod): self
    {
        $this->lastmod = $lastmod;

        return $this;
    }

    public function getChangefreq(): ?string
    {
        return $this->changefreq;
    }

    public function setChangefreq(?string $changefreq): self
    {
        $this->changefreq = $changefreq;

        return $this;
    }

    #[Pure]
    public function getPriority(): string
    {
        return number_format($this->priority, 2);
    }

    public function setPriority(float $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    public function addVideo(Video $video): self
    {
        $this->videos[] = $video;

        return $this;
    }

    public function addImage(Image $image): self
    {
        $this->images[] = $image;

        return $this;
    }

    #[Pure]
    public function toArray(): array
    {
        return get_object_vars($this);
    }

    #[Pure]
    public function hasVideos(): bool
    {
        return !empty($this->getVideos());
    }

    public function getVideos(): array
    {
        return $this->videos;
    }

    public function setVideos(array $videos): self
    {
        $this->videos = $videos;

        return $this;
    }

    #[Pure]
    public function hasImages(): bool
    {
        return !empty($this->getImages());
    }

    public function getImages(): array
    {
        return $this->images;
    }

    public function setImages(array $images): self
    {
        $this->images = $images;

        return $this;
    }
}
