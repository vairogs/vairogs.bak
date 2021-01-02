<?php declare(strict_types = 1);

namespace Vairogs\Component\Sitemap\Model;

use DateTime;
use DateTimeInterface;
use Symfony\Component\Validator\Constraints as Assert;

class SitemapIndex
{
    /**
     * Required
     * URL of the sitemap index.
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
     * @return string
     */
    public function getLoc(): string
    {
        return $this->loc;
    }

    /**
     * @param string $loc
     *
     * @return SitemapIndex
     */
    public function setLoc(string $loc): SitemapIndex
    {
        $this->loc = $loc;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getLastmod(): ?DateTime
    {
        return $this->lastmod;
    }

    /**
     * @param DateTimeInterface|null $lastmod
     *
     * @return SitemapIndex
     */
    public function setLastmod(?DateTimeInterface $lastmod): SitemapIndex
    {
        $this->lastmod = $lastmod;

        return $this;
    }
}
