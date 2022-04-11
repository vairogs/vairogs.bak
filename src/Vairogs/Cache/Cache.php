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
    final public const DEFAULT_LIFETIME = 86400;
    private const ALGORITHM = 'sha1';

    public function __construct(private readonly int $expires = self::DEFAULT_LIFETIME, private readonly array $attributes = [], private string $strategy = Strategy::ALL, private readonly string $algorithm = self::ALGORITHM, private mixed $data = null)
    {
    }

    public function getKey(string $prefix = ''): string
    {
        if (!is_array(value: $this->data)) {
            return hash(algo: $this->algorithm, data: $prefix . '_' . ($this->data ?: ''));
        }

        $value = $this->data;

        if (!empty($this->attributes)) {
            $value = Iteration::arrayIntersectKeyRecursive(first: $this->data, second: Iteration::arrayFlipRecursive(input: $this->attributes));
        }

        return hash(algo: $this->algorithm, data: $prefix . '_' . str_replace(search: '=', replace: '_', subject: http_build_query(data: $value, arg_separator: '_')));
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

    public function getExpires(): int
    {
        return $this->expires;
    }
}
