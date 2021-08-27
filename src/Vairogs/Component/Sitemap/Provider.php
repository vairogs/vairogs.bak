<?php declare(strict_types = 1);

namespace Vairogs\Component\Sitemap;

use Vairogs\Component\Sitemap\Model\Sitemap;

interface Provider
{
    public function populate(string $host): Sitemap;
}
