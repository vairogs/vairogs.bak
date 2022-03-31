<?php declare(strict_types = 1);

namespace Vairogs\Auth\OpenIDConnect\Configuration\Constraint;

use DateTimeInterface;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\Validation\ConstraintViolation;
use Vairogs\Auth\OpenIDConnect\Exception\InvalidConstraintException;
use function sprintf;

class LesserOrEqual extends AbstractConstraint
{
    public function __construct(private readonly mixed $expected)
    {
    }

    public function assert(Token $token): void
    {
        parent::assert(token: $token);
        $this->assertClaimSet();

        /** @var string $this->claim */
        $value = $token->claims()->get(name: $this->claim);
        if ($value instanceof DateTimeInterface) {
            $value = $value->getTimestamp();
        }
        if ($this->expected < $value) {
            $message = sprintf('Given value %s should be lesser or equal to %s', $value, $this->expected);
            if ($this->required) {
                throw new ConstraintViolation(message: $message);
            }

            throw new InvalidConstraintException(message: $message);
        }
    }
}
