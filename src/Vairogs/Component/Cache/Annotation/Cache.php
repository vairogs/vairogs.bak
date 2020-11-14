<?php declare(strict_types = 1);

namespace Vairogs\Component\Cache\Annotation;

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
class Cache
{
    /**
     * @var string
     */
    private const ALGORITHM = 'sha1';

    public int $expires;

    public null|string|array $data;

    public array $attributes = [];

    public string $strategy = Strategy::ALL;

    /**
     * @param string $prefix
     * @return string
     */
    public function getKey(string $prefix = ''): string
    {
        $value = $this->getData();
        if (!is_array($value)) {
            $key = $value ?: '';
        } else {
            if (!empty($this->getAttributes())) {
                $flipped = Iter::arrayFlipRecursive($this->getAttributes());
                $value = Iter::arrayIntersectKeyRecursive($value, $flipped);
            }

            $key = str_replace('=', '_', http_build_query($value, '', '_'));
        }

        return hash(self::ALGORITHM, $prefix . '_' . $key);
    }

    /**
     * @return null|array|string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param $data
     *
     * @return Cache
     */
    public function setData($data): Cache
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @return null|string
     */
    public function getStrategy(): ?string
    {
        return $this->strategy;
    }

    /**
     * @param string $strategy
     *
     * @return Cache
     */
    public function setStrategy(string $strategy): Cache
    {
        $this->strategy = $strategy;

        return $this;
    }

    /**
     * @return null|int
     */
    public function getExpires(): ?int
    {
        return $this->expires;
    }
}
