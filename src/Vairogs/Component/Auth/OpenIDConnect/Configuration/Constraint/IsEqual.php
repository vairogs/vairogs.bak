<?php declare(strict_types = 1);

namespace Vairogs\Component\Auth\OpenIDConnect\Configuration\Constraint;

use Lcobucci\JWT\Token;
use Lcobucci\JWT\UnencryptedToken;
use Lcobucci\JWT\Validation\Constraint;
use Lcobucci\JWT\Validation\ConstraintViolation;
use function sprintf;

final class IsEqual implements Constraint
{
    public function __construct(private string $claim, private mixed $expected, private bool $required = false)
    {
    }

    public function assert(Token $token): void
    {
        if (!$token instanceof UnencryptedToken) {
            throw new ConstraintViolation(message: 'You should pass a plain token');
        }

        if (true === $this->required) {
            if (!$token->claims()->has($this->claim)) {
                throw new ConstraintViolation(message: sprintf('%s claim is required', $this->claim));
            }

            if ($this->expected !== $value = $token->claims()->get($this->claim)) {
                throw new ConstraintViolation(message: sprintf('%s is not equal to expected %s', $value, $this->expected));
            }
        }
    }
}
