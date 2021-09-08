<?php declare(strict_types = 1);

namespace Vairogs\Component\Cache\Annotation;

use Attribute;
use Doctrine\Common\Annotations\Annotation;
use Vairogs\Component\Cache\Utils\Strategy;
use Vairogs\Component\Utils\Helper\Iter;
use function hash;
use function http_build_query;
use function is_array;
use function str_replace;

/**
 * @Annotation
 * @Annotation\Target({"METHOD"})
 */
#[Attribute]
class Cache
{
    private const ALGORITHM = 'sha1';

    public ?int $expires = null;
    public null|string|array $data;
    public array $attributes = [];
    public string $strategy = Strategy::ALL;

    public function getKey(string $prefix = ''): string
    {
        $value = $this->data;

        if (!is_array($value)) {
            $key = $value ?: '';
        } else {
            if (!empty($this->attributes)) {
                $flipped = Iter::arrayFlipRecursive($this->attributes);
                $value = Iter::arrayIntersectKeyRecursive($value, $flipped);
            }

            $key = str_replace('=', '_', http_build_query($value, '', '_'));
        }

        return hash(self::ALGORITHM, $prefix . '_' . $key);
    }

    public function getData(): array|string|null
    {
        return $this->data;
    }

    public function setData(mixed $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getStrategy(): string
    {
        return $this->strategy;
    }

    public function setStrategy(string $strategy): static
    {
        $this->strategy = $strategy;

        return $this;
    }

    public function getExpires(): ?int
    {
        return $this->expires;
    }
}
