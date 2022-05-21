<?php declare(strict_types = 1);

namespace Vairogs\Cache;

use Attribute;
use Vairogs\Extra\Constants\Definition;
use Vairogs\Utils\Helper\Iteration;
use function hash;
use function hash_algos;
use function http_build_query;
use function in_array;
use function is_array;
use function str_replace;

#[Attribute(Attribute::TARGET_METHOD)]
final class Cache
{
    public function __construct(private readonly int $expires = Definition::DEFAULT_LIFETIME, private readonly array $attributes = [], private readonly string $strategy = Strategy::ALL, private string $algorithm = Definition::HASH_ALGORITHM, private mixed $data = null)
    {
        if (!in_array(needle: $this->algorithm, haystack: hash_algos(), strict: true)) {
            $this->algorithm = Definition::HASH_ALGORITHM;
        }
    }

    public function getKey(string $prefix = ''): string
    {
        if (!is_array(value: $this->data)) {
            return hash(algo: $this->algorithm, data: $prefix . '_' . ($this->data ?: ''));
        }

        $value = $this->data;

        if ([] !== $this->attributes) {
            $value = (new Iteration())->arrayIntersectKeyRecursive(first: $this->data, second: (new Iteration())->arrayFlipRecursive(input: $this->attributes));
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

    public function getExpires(): int
    {
        return $this->expires;
    }
}
