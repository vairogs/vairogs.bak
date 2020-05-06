<?php declare(strict_types = 1);

namespace Vairogs\Component\Sitemap\Model;

use DateTime;
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
     * @var string
     */
    protected string $loc;

    /**
     * Optional
     * The date of last modification of the file.
     * @var null|DateTime
     * @Assert\Type(type="datetime")
     */
    protected ?DateTime$lastmod;

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
     * @param DateTime|null $lastmod
     *
     * @return SitemapIndex
     */
    public function setLastmod(?DateTime $lastmod): SitemapIndex
    {
        $this->lastmod = $lastmod;

        return $this;
    }
}
