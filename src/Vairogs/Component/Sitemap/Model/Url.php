<?php declare(strict_types = 1);

namespace Vairogs\Component\Sitemap\Model;

use DateTime;
use DateTimeInterface;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Validator\Constraints as Assert;
use function get_object_vars;
use function number_format;

class Url
{
    /**
     * Required
     * URL of the page.
     * This URL must begin with the protocol (such as http) and end with a trailing slash, if your web server requires
     * it. This value must be less than 2,048 characters.
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     * @Assert\Length(
     *     min="10",
     *     max="2048"
     * )
     */
    protected string $loc;

    /**
     * Optional
     * The date of last modification of the file.
     * @Assert\Type(type="datetime")
     */
    protected ?DateTime$lastmod = null;

    /**
     * Optional
     * How frequently the page is likely to change.
     * This value provides general information to search engines and may not correlate exactly to how often they crawl
     * the page.
     * @Assert\Type(type="string")
     * @Assert\Choice(callback={"Vairogs\Component\Sitemap\Utils\Constant\ChangeFrequency", "getChangeFrequencies"})
     */
    protected ?string $changefreq = null;

    /**
     * Optional
     * The priority of this URL relative to other URLs on your site.
     * Valid values range from 0.0 to 1.0.
     * This value does not affect how your pages are compared to pages on other sites—it only lets the search engines
     * know which pages you deem most important for the crawlers.
     * @Assert\NotBlank()
     * @Assert\Type(type="float")
     * @Assert\Range(
     *     min="0.0",
     *     max="1.0"
     * )
     */
    protected float $priority = 0.5;

    /**
     * @var Video[]
     * @Assert\Valid()
     */
    protected array $videos = [];

    /**
     * @var Image[]
     * @Assert\Valid()
     */
    protected array $images = [];

    /**
     * @return string
     */
    public function getLoc(): string
    {
        return $this->loc;
    }

    /**
     * @param string $loc
     *
     * @return self
     */
    public function setLoc(string $loc): self
    {
        $this->loc = $loc;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getLastmod(): ?string
    {
        return null !== $this->lastmod ? $this->lastmod->format(DateTime::ATOM) : null;
    }

    /**
     * @param DateTimeInterface|null $lastmod
     *
     * @return self
     */
    public function setLastmod(?DateTimeInterface $lastmod): self
    {
        $this->lastmod = $lastmod;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getChangefreq(): ?string
    {
        return $this->changefreq;
    }

    /**
     * @param string|null $changefreq
     *
     * @return self
     */
    public function setChangefreq(?string $changefreq): self
    {
        $this->changefreq = $changefreq;

        return $this;
    }

    /**
     * @return string
     */
    #[Pure] public function getPriority(): string
    {
        return number_format($this->priority, 2);
    }

    /**
     * @param float $priority
     *
     * @return self
     */
    public function setPriority(float $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * @param Video $video
     *
     * @return self
     */
    public function addVideo(Video $video): self
    {
        $this->videos[] = $video;

        return $this;
    }

    /**
     * @param Image $image
     *
     * @return self
     */
    public function addImage(Image $image): self
    {
        $this->images[] = $image;

        return $this;
    }

    /**
     * @return array
     */
    #[Pure] public function toArray(): array
    {
        return get_object_vars($this);
    }

    /**
     * @return bool
     */
    #[Pure] public function hasVideos(): bool
    {
        return !empty($this->getVideos());
    }

    /**
     * @return Video[]
     */
    public function getVideos(): array
    {
        return $this->videos;
    }

    /**
     * @param Video[] $videos
     *
     * @return self
     */
    public function setVideos(array $videos): self
    {
        $this->videos = $videos;

        return $this;
    }

    /**
     * @return bool
     */
    #[Pure] public function hasImages(): bool
    {
        return !empty($this->getImages());
    }

    /**
     * @return Image[]
     */
    public function getImages(): array
    {
        return $this->images;
    }

    /**
     * @param Image[] $images
     *
     * @return self
     */
    public function setImages(array $images): self
    {
        $this->images = $images;

        return $this;
    }
}
