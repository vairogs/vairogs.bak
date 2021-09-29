<?php declare(strict_types = 1);

namespace Vairogs\Auth\OpenIDConnect\Configuration;

use Exception;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\Validation\Constraint;
use Vairogs\Auth\OpenIDConnect\Configuration\Constraint\AbstractConstraint;
use Vairogs\Auth\OpenIDConnect\Exception\InvalidConstraintException;
use Vairogs\Auth\OpenIDConnect\Exception\OpenIDConnectException;
use Vairogs\Component\Utils\Helper\Text;
use function http_build_query;
use function sprintf;

class ValidatorChain implements Constraint
{
    /**
     * @var Constraint[]
     */
    protected array $assertions = [];

    public function assert(Token $token): void
    {
        $valid = true;
        $messages = [];

        foreach ($this->assertions as $claim => $assertion) {
            if ($assertion instanceof AbstractConstraint) {
                $claim = $assertion->getClaim() ?? Text::getLastPart(string: $assertion::class, delimiter: '\\');
            }

            try {
                $assertion->assert(token: $token);
            } catch (InvalidConstraintException $e) {
                $messages[$claim] = $e->getMessage();
            } catch (Exception $e) {
                $valid = false;
                $messages[$claim] = $e->getMessage();
            }
        }

        if (false === $valid) {
            throw new OpenIDConnectException(message: sprintf('Unable to verify JWT claims: %s', http_build_query(data: $messages)));
        }
    }

    public function setAssertions(array $assertions): self
    {
        $this->assertions = $assertions;

        return $this;
    }
}
