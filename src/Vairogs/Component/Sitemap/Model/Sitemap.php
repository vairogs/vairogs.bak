<?php declare(strict_types = 1);

namespace Vairogs\Sitemap\Model;

use Symfony\Component\Validator\Constraints as Assert;
use function method_exists;

class Sitemap
{
    /**
     * @var Url[]
     * @Assert\Valid()
     */
    protected $urls = [];

    /**
     * @param Url $url
     *
     * @return Sitemap
     */
    public function addUrl(Url $url): Sitemap
    {
        $this->urls[] = $url;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasImages(): bool
    {
        foreach ($this->getUrls() as $url) {
            if ($url->hasImages()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return Url[]
     */
    public function getUrls(): array
    {
        return $this->urls;
    }

    /**
     * @param Url[] $urls
     *
     * @return Sitemap
     */
    public function setUrls(array $urls): Sitemap
    {
        $this->urls = $urls;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasVideos(): bool
    {
        foreach ($this->getUrls() as $url) {
            if ($url->hasVideos()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function hasAlternates(): bool
    {
        foreach ($this->getUrls() as $url) {
            if (method_exists($url, 'hasAlternates') && $url->hasAlternates()) {
                return true;
            }
        }

        return false;
    }
}
