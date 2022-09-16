<?php declare(strict_types = 1);

namespace Vairogs\Auth\OpenIDConnect\Configuration\Constraint;

use JetBrains\PhpStorm\Pure;
use Lcobucci\JWT\Signer;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Validation\Constraint;
use Vairogs\Auth\OpenIDConnect\Configuration\IdToken;

final class SignedWith extends AbstractConstraint
{
    private readonly Constraint $constraint;

    #[Pure]
    public function __construct(Signer $signer, Key $key)
    {
        $this->constraint = new Constraint\SignedWith($signer, $key);
    }

    public function validate(IdToken $token): void
    {
        parent::validate(token: $token);

        $this->constraint->assert(token: $token);
    }
}
