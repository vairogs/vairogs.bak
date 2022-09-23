<?php declare(strict_types = 1);

namespace Vairogs\Auth\OpenIDConnect\Configuration;

use Exception;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\Validation\Constraint as JWTConstraint;
use Vairogs\Auth\OpenIDConnect\Configuration\Constraint\Constraint;
use Vairogs\Auth\OpenIDConnect\Exception\InvalidConstraintException;
use Vairogs\Auth\OpenIDConnect\Exception\OpenIDConnectException;
use Vairogs\Utils\Helper\Text;

use function http_build_query;
use function sprintf;

class ValidatorChain implements JWTConstraint
{
    /**
     * @var Constraint[]
     */
    protected array $assertions = [];
    private array $messages = [];
    private bool $valid = true;

    public function assert(Token $token): void
    {
    }

    public function validate(IdToken $token): void
    {
        foreach ($this->assertions as $claim => $assertion) {
            $this->assertConstraint(constraint: $assertion, claim: $claim, token: $token);
        }

        if (!$this->valid) {
            throw new OpenIDConnectException(message: sprintf('Unable to verify JWT claims: %s', http_build_query(data: $this->messages)));
        }
    }

    public function setAssertions(array $assertions): self
    {
        $this->assertions = $assertions;

        return $this;
    }

    private function assertConstraint(JWTConstraint $constraint, string $claim, IdToken $token): void
    {
        if ($constraint instanceof Constraint) {
            $claim = $constraint->getClaim() ?? (new Text())->getLastPart(text: $constraint::class, delimiter: '\\');
        }

        try {
            if ($constraint instanceof Constraint) {
                $constraint->validate(token: $token);
            } else {
                $constraint->assert(token: $token);
            }
        } catch (InvalidConstraintException $e) {
            $this->messages[$claim] = $e->getMessage();
        } catch (Exception $e) {
            $this->valid = false;
            $this->messages[$claim] = $e->getMessage();
        }
    }
}
