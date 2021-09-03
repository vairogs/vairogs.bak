<?php declare(strict_types = 1);

namespace Vairogs\Component\Sitemap\Builder;

use Vairogs\Component\Sitemap\Model\Url;
use function method_exists;
use function sprintf;
use function ucfirst;

abstract class AbstractBuilder implements Builder
{
    protected function getBufferValue(Url $url, string $key): string
    {
        if ($getter = $this->getGetterValue($url, $key)) {
            return "\t" . sprintf('<%s>', $key) . $getter . sprintf('</%s>', $key) . "\n";
        }

        return '';
    }

    protected function getGetterValue(Url $url, string $key): ?string
    {
        if (method_exists($url, $getter = 'get' . ucfirst($key)) && !empty($url->$getter())) {
            return (string)$url->$getter();
        }

        return null;
    }
}
