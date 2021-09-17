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
        if (($expected = $builder->getType()) !== ($actual = gettype(value: $this->buffer))) {
            throw new InvalidArgumentException(message: sprintf('Director __constructor parameter must be %s, %s given', $expected, $actual));
        }

        $builder->start(buffer: $this->buffer);
        $builder->build(buffer: $this->buffer);
        $builder->end(buffer: $this->buffer);

        return $this->buffer;
    }
}
