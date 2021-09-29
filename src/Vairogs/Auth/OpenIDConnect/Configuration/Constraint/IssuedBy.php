<?php declare(strict_types = 1);

namespace Vairogs\Auth\OpenIDConnect\Configuration\Constraint;

use JetBrains\PhpStorm\Pure;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\Validation\Constraint;

final class IssuedBy extends AbstractConstraint
{
    private Constraint $constraint;

    #[Pure]
    public function __construct(string ...$issuers)
    {
        $this->constraint = new Constraint\IssuedBy(...$issuers);
    }

    public function assert(Token $token): void
    {
        parent::assert(token: $token);

        $this->constraint->assert(token: $token);
    }
}
