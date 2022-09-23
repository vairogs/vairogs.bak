<?php declare(strict_types = 1);

namespace Vairogs\Auth\OpenIDConnect\Configuration\Constraint;

use DateTimeInterface;
use Lcobucci\JWT\Validation\ConstraintViolation;
use Vairogs\Auth\OpenIDConnect\Configuration\IdToken;
use Vairogs\Auth\OpenIDConnect\Exception\InvalidConstraintException;

use function sprintf;

class GreaterOrEqual extends Constraint
{
    public function __construct(private readonly int $expected)
    {
    }

    public function validate(IdToken $token): void
    {
        parent::validate(token: $token);
        $this->assertClaimSet();

        $value = $token->claims()->get(name: $this->claim);
        if ($value instanceof DateTimeInterface) {
            $value = $value->getTimestamp();
        }

        if ($this->expected > $value) {
            $message = sprintf('Given value %s should be greater or equal to %s', $value, $this->expected);
            if ($this->required) {
                throw new ConstraintViolation(message: $message);
            }

            throw new InvalidConstraintException(message: $message);
        }
    }
}
