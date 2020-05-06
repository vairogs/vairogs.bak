<?php declare(strict_types = 1);

namespace Vairogs\Component\Sitemap\Model;

use Symfony\Component\Validator\Constraints as Assert;

class RichUrl extends Url
{
    /**
     * Optional
     * The fully-qualified URL for the version of this page for the specified language/region.
     * @var string[]
     * @Assert\All(
     *     @Assert\Type(type="string"),
     *     @Assert\NotBlank()
     * )
     */
    protected array $alternateUrl = [];

    /**
     * @param string $locale
     * @param string $url
     *
     * @return Url
     */
    public function addAlternateUrl(string $locale, string $url): Url
    {
        $this->alternateUrl[$locale] = $url;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasAlternates(): bool
    {
        return !empty($this->getAlternateUrls());
    }

    /**
     * @return array
     */
    public function getAlternateUrls(): array
    {
        return $this->alternateUrl;
    }
}
