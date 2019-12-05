<?php declare(strict_types = 1);

namespace Vairogs\Sitemap;

use Vairogs\Sitemap\Model\Sitemap;

interface Provider
{
    /**
     * @param string $host
     *
     * @return Sitemap
     */
    public function populate(string $host): Sitemap;
}
