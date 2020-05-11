<?php declare(strict_types = 1);

namespace Vairogs\Component\Sitemap;

use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Vairogs\Component\Sitemap\DependencyInjection\VairogsSitemapExtension;

class Sitemap extends Bundle
{
    /**
     * @var string
     */
    public const ALIAS = 'sitemap';

    /**
     * @return null|ExtensionInterface
     */
    public function getContainerExtension(): ?ExtensionInterface
    {
        return $this->extension ?? new VairogsSitemapExtension();
    }
}
