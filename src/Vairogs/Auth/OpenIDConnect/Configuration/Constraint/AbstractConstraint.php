<?php declare(strict_types = 1);

namespace Vairogs\Auth\OpenIDConnect\Configuration\Constraint;

use Lcobucci\JWT\Token;
use Lcobucci\JWT\UnencryptedToken;
use Lcobucci\JWT\Validation\Constraint;
use Lcobucci\JWT\Validation\ConstraintViolation;
use function sprintf;

abstract class AbstractConstraint implements Constraint
{
    protected ?string $claim = null;
    protected bool $required = false;

    public function assert(Token $token): void
    {
        if (!$token instanceof UnencryptedToken) {
            throw new ConstraintViolation(message: 'You should pass a plain token');
        }

        if (null !== $this->claim && $this->required && !$token->claims()->has(name: $this->claim)) {
            throw new ConstraintViolation(message: sprintf('%s claim is required', $this->claim));
        }
    }

    public function setClaim(?string $claim): self
    {
        $this->claim = $claim;

        return $this;
    }

    public function setRequired(bool $required): self
    {
        $this->required = $required;

        return $this;
    }

    public function getClaim(): ?string
    {
        return $this->claim;
    }

    protected function assertClaimSet(): void
    {
        if (null === $this->claim) {
            throw new ConstraintViolation(message: 'Missing name of claim to assert');
        }
    }
}
