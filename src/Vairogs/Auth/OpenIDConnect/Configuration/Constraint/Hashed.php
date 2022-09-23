<?php declare(strict_types = 1);

namespace Vairogs\Auth\OpenIDConnect\Configuration\Constraint;

use Lcobucci\JWT\Validation\ConstraintViolation;
use Vairogs\Auth\OpenIDConnect\Configuration\IdToken;
use Vairogs\Utils\Helper\Identification;

use function sprintf;

final class Hashed extends Constraint
{
    public function validate(IdToken $token): void
    {
        parent::validate(token: $token);
        $this->assertClaimSet();

        $hash = (new Identification())->getHash(text: $token->getAccessTokenString() ?? '');
        if ($hash !== $token->claims()->get(name: $this->claim)) {
            throw new ConstraintViolation(message: sprintf('Invalid %s', $this->claim));
        }
    }
}
