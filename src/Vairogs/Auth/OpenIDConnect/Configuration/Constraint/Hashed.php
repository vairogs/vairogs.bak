<?php declare(strict_types = 1);

namespace Vairogs\Auth\OpenIDConnect\Configuration\Constraint;

use Lcobucci\JWT\Token;
use Lcobucci\JWT\Validation\ConstraintViolation;
use Vairogs\Auth\OpenIDConnect\Configuration\IdToken;
use Vairogs\Component\Utils\Helper\Text;
use function sprintf;

final class Hashed extends AbstractConstraint
{
    public function assert(Token $token): void
    {
        parent::assert(token: $token);
        /* @var IdToken $token */
        $this->assertClaimSet();

        /** @var string $this->claim */
        $hash = Text::getHash(hashable: $token->getAccessTokenString());
        if ($hash !== $token->claims()->get(name: $this->claim)) {
            throw new ConstraintViolation(message: sprintf('Invalid %s', $this->claim));
        }
    }
}
