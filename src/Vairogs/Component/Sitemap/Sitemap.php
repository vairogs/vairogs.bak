<?php declare(strict_types = 1);

namespace Vairogs\Sitemap;

use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Vairogs\Sitemap\DependencyInjection\VairogsSitemapExtension;

class Sitemap extends Bundle
{
    /**
     * @return null|ExtensionInterface
     */
    public function getContainerExtension(): ?ExtensionInterface
    {
        return $this->extension ?? new VairogsSitemapExtension();
    }
}
