<?php declare(strict_types = 1);

namespace Vairogs\Auth\OpenIDConnect\Configuration\Constraint;

use JetBrains\PhpStorm\Pure;
use Lcobucci\JWT\Validation\Constraint;
use Vairogs\Auth\OpenIDConnect\Configuration\IdToken;

final class IssuedBy extends AbstractConstraint
{
    private readonly Constraint $constraint;

    #[Pure]
    public function __construct(string ...$issuers)
    {
        $this->constraint = new Constraint\IssuedBy(...$issuers);
    }

    public function validate(IdToken $token): void
    {
        parent::validate(token: $token);

        $this->constraint->assert(token: $token);
    }
}
