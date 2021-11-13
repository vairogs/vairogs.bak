<?php declare(strict_types = 1);

namespace Vairogs\Auth\OpenIDConnect\Configuration;

use DateTimeInterface;
use JetBrains\PhpStorm\Pure;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\Token\DataSet;
use Lcobucci\JWT\Token\Signature;
use Lcobucci\JWT\UnencryptedToken;

class IdToken implements UnencryptedToken
{
    protected ?string $accessTokenString;

    public function __construct(private Token|Token\Plain $token)
    {
    }

    public function getAccessTokenString(): ?string
    {
        return $this->accessTokenString;
    }

    public function setAccessTokenString(?string $accessTokenString): self
    {
        $this->accessTokenString = $accessTokenString;

        return $this;
    }

    #[Pure]
    public function hasClaim(string $claim): bool
    {
        return $this->claims()
            ->has(name: $claim);
    }

    #[Pure]
    public function claims(): DataSet
    {
        return $this->token->claims();
    }

    public function hasBeenIssuedBefore(DateTimeInterface $now): bool
    {
        return $this->token->hasBeenIssuedBefore(now: $now);
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
        return $this->token->isExpired(now: $now);
    }

    public function isIdentifiedBy(string $id): bool
    {
        return $this->token->isIdentifiedBy(id: $id);
    }

    public function isMinimumTimeBefore(DateTimeInterface $now): bool
    {
        return $this->token->isMinimumTimeBefore(now: $now);
    }

    public function isPermittedFor(string $audience): bool
    {
        return $this->token->isPermittedFor(audience: $audience);
    }

    public function isRelatedTo(string $subject): bool
    {
        return $this->token->isRelatedTo(subject: $subject);
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
            ->get(name: $claim);
    }
}
