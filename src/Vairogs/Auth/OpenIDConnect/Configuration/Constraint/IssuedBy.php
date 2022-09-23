<?php declare(strict_types = 1);

namespace Vairogs\Auth\OpenIDConnect\Configuration\Constraint;

use JetBrains\PhpStorm\Pure;
use Lcobucci\JWT\Validation\Constraint as JWTConstraint;
use Vairogs\Auth\OpenIDConnect\Configuration\IdToken;

final class IssuedBy extends Constraint
{
    private readonly JWTConstraint $constraint;

    #[Pure]
    public function __construct(string ...$issuers)
    {
        $this->constraint = new JWTConstraint\IssuedBy(...$issuers);
    }

    public function validate(IdToken $token): void
    {
        parent::validate(token: $token);

        $this->constraint->assert(token: $token);
    }
}
