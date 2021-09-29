<?php declare(strict_types = 1);

namespace Vairogs\Auth\OpenIDConnect\Configuration\Constraint;

use JetBrains\PhpStorm\Pure;
use Lcobucci\JWT\Signer;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\Validation\Constraint;

final class SignedWith extends AbstractConstraint
{
    private Constraint $constraint;

    #[Pure]
    public function __construct(Signer $signer, Signer\Key $key)
    {
        $this->constraint = new Constraint\SignedWith($signer, $key);
    }

    public function assert(Token $token): void
    {
        parent::assert(token: $token);

        $this->constraint->assert(token: $token);
    }
}
