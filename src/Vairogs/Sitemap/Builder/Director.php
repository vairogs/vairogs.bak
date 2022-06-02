<?php declare(strict_types = 1);

namespace Vairogs\Sitemap\Builder;

use InvalidArgumentException;
use function gettype;
use function sprintf;

class Director
{
    /** @noinspection PhpPropertyCanBeReadonlyInspection */
    public function __construct(private mixed $buffer)
    {
    }

    public function build(Builder $builder): mixed
    {
        if (($type = $builder->getType()) !== ($actual = gettype(value: $this->buffer))) {
            throw new InvalidArgumentException(message: sprintf('Director __constructor parameter must be %s, %s given', $type, $actual));
        }

        $builder->start(buffer: $this->buffer);
        $builder->build(buffer: $this->buffer);
        $builder->end(buffer: $this->buffer);

        return $this->buffer;
    }
}
