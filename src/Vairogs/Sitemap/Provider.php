<?php declare(strict_types = 1);

namespace Vairogs\Sitemap;

use Vairogs\Sitemap\Model\Sitemap;

interface Provider
{
    public function populate(string $host): Sitemap;
}
