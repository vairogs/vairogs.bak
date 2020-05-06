<?php declare(strict_types = 1);

namespace Vairogs\Component\Sitemap\Model;

use Symfony\Component\Validator\Constraints as Assert;

class Image
{
    /**
     * Required
     * The URL of the image.
     * @var string
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
     * The caption of the image.
     * @var null|string
     * @Assert\Type(type="string")
     */
    protected ?string $caption;

    /**
     * Optional
     * The geographic location of the image.
     * @var null|string
     * @Assert\Type(type="string")
     */
    protected ?string $geoLocation;

    /**
     * Optional
     * The title of the image.
     * @var null|string
     * @Assert\Type(type="string")
     */
    protected ?string $title;

    /**
     * Optional
     * A URL to the license of the image.
     * @var null|string
     * @Assert\Type(type="string")
     */
    protected ?string $license;

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
     * @return Image
     */
    public function setLoc(string $loc): Image
    {
        $this->loc = $loc;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCaption(): ?string
    {
        return $this->caption;
    }

    /**
     * @param string|null $caption
     *
     * @return Image
     */
    public function setCaption(?string $caption): Image
    {
        $this->caption = $caption;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getGeoLocation(): ?string
    {
        return $this->geoLocation;
    }

    /**
     * @param string|null $geoLocation
     *
     * @return Image
     */
    public function setGeoLocation(?string $geoLocation): Image
    {
        $this->geoLocation = $geoLocation;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     *
     * @return Image
     */
    public function setTitle(?string $title): Image
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLicense(): ?string
    {
        return $this->license;
    }

    /**
     * @param string|null $license
     *
     * @return Image
     */
    public function setLicense(?string $license): Image
    {
        $this->license = $license;

        return $this;
    }
}
