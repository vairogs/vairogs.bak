<?php declare(strict_types = 1);

namespace Vairogs\Component\Cache\Annotation;

use Attribute;
use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\NamedArgumentConstructor;
use Vairogs\Component\Cache\Utils\Strategy;
use Vairogs\Component\Utils\Helper\Iter;
use function hash;
use function http_build_query;
use function is_array;
use function str_replace;

/**
 * @Annotation
 * @Annotation\Target({"METHOD"})
 * @NamedArgumentConstructor()
 */
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

        if (!is_array($value)) {
            $key = $value ?: '';
        } else {
            if (!empty($this->attributes)) {
                $flipped = Iter::arrayFlipRecursive($this->attributes);
                $value = Iter::arrayIntersectKeyRecursive($value, $flipped);
            }

            $key = str_replace('=', '_', http_build_query($value, '', '_'));
        }

        return hash($this->algorithm, $prefix . '_' . $key);
    }

    public function getData(): mixed
    {
        return $this->data;
    }

    public function setData(mixed $data): Cache
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

    public function setStrategy(string $strategy): Cache
    {
        $this->strategy = $strategy;

        return $this;
    }

    public function getExpires(): ?int
    {
        return $this->expires;
    }
}
