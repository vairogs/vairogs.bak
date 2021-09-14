<?php declare(strict_types = 1);

namespace Vairogs\Component\Auth\OpenIDConnect\Configuration;

use DateTimeInterface;
use JetBrains\PhpStorm\Pure;
use Lcobucci\JWT\Token\DataSet;
use Lcobucci\JWT\Token\Plain;
use Lcobucci\JWT\Token\Signature;
use Lcobucci\JWT\UnencryptedToken;

class IdToken implements UnencryptedToken
{
    public function __construct(private Token | Plain $token)
    {
    }

    #[Pure]
    public function hasClaim(string $claim): bool
    {
        return $this->claims()
            ->has($claim);
    }

    #[Pure]
    public function claims(): DataSet
    {
        return $this->token->claims();
    }

    public function hasBeenIssuedBefore(DateTimeInterface $now): bool
    {
        return $this->token->hasBeenIssuedBefore($now);
    }

    public function hasBeenIssuedBy(string ...$issuers): bool
    {
        return $this->token->hasBeenIssuedBy(...$issuers);
    }

    public function headers(): DataSet
    {
        return $this->token->headers();
    }

    public function isExpired(DateTimeInterface $now): bool
    {
        return $this->token->isExpired($now);
    }

    public function isIdentifiedBy(string $id): bool
    {
        return $this->token->isIdentifiedBy($id);
    }

    public function isMinimumTimeBefore(DateTimeInterface $now): bool
    {
        return $this->token->isMinimumTimeBefore($now);
    }

    public function isPermittedFor(string $audience): bool
    {
        return $this->token->isPermittedFor($audience);
    }

    public function isRelatedTo(string $subject): bool
    {
        return $this->token->isRelatedTo($subject);
    }

    public function payload(): string
    {
        return $this->token->payload();
    }

    #[Pure]
    public function signature(): Signature
    {
        return $this->token->signature();
    }

    public function toString(): string
    {
        return $this->token->toString();
    }

    public function getClaim(string $claim): mixed
    {
        return $this->claims()
            ->get($claim);
    }
}
