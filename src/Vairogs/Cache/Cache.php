<?php declare(strict_types = 1);

namespace Vairogs\Cache;

use Attribute;
use Vairogs\Cache\Utils\Strategy;
use Vairogs\Utils\Helper\Iteration;
use function hash;
use function http_build_query;
use function is_array;
use function str_replace;

#[Attribute(Attribute::TARGET_METHOD)]
final class Cache
{
    private const ALGORITHM = 'sha1';

    public function __construct(private ?int $expires = null, private array $attributes = [], private string $strategy = Strategy::ALL, private string $algorithm = self::ALGORITHM, private mixed $data = null)
    {
    }

    public function getKey(string $prefix = ''): string
    {
        $value = $this->data;

        if (!is_array(value: $value)) {
            $key = $value ?: '';
        } else {
            if (!empty($this->attributes)) {
                $flipped = Iteration::arrayFlipRecursive(input: $this->attributes);
                $value = Iteration::arrayIntersectKeyRecursive(first: $value, second: $flipped);
            }

            $key = str_replace(search: '=', replace: '_', subject: http_build_query(data: $value, arg_separator: '_'));
        }

        return hash(algo: $this->algorithm, data: $prefix . '_' . $key);
    }

    public function getData(): mixed
    {
        return $this->data;
    }

    public function setData(mixed $data): self
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

    public function setStrategy(string $strategy): self
    {
        $this->strategy = $strategy;

        return $this;
    }

    public function getExpires(): ?int
    {
        return $this->expires;
    }
}
