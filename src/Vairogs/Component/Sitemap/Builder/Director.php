<?php declare(strict_types = 1);

namespace Vairogs\Component\Sitemap\Builder;

use InvalidArgumentException;
use function gettype;
use function sprintf;

class Director
{
    public function __construct(private mixed $buffer)
    {
    }

    public function build(Builder $builder): mixed
    {
        if (($expected = $builder->getType()) !== ($actual = gettype($this->buffer))) {
            throw new InvalidArgumentException(sprintf('Director __constructor parameter must be %s, %s given', $expected, $actual));
        }

        $builder->start($this->buffer);
        $builder->build($this->buffer);
        $builder->end($this->buffer);

        return $this->buffer;
    }
}
