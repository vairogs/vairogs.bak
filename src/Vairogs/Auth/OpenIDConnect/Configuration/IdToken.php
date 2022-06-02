<?php declare(strict_types = 1);

namespace Vairogs\Auth\OpenIDConnect\Configuration;

use DateTimeInterface;
use Lcobucci\JWT\Token\DataSet;
use Lcobucci\JWT\Token\Signature;
use Lcobucci\JWT\UnencryptedToken;

class IdToken implements UnencryptedToken
{
    protected ?string $accessTokenString = null;

    public function __construct(private readonly UnencryptedToken $unencryptedToken)
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

    public function hasClaim(string $claim): bool
    {
        return $this->claims()->has(name: $claim);
    }

    public function claims(): DataSet
    {
        return $this->unencryptedToken->claims();
    }

    public function hasBeenIssuedBefore(DateTimeInterface $now): bool
    {
        return $this->unencryptedToken->hasBeenIssuedBefore(now: $now);
    }

    public function hasBeenIssuedBy(string ...$issuers): bool
    {
        return $this->unencryptedToken->hasBeenIssuedBy(...$issuers);
    }

    public function headers(): DataSet
    {
        return $this->unencryptedToken->headers();
    }

    public function isExpired(DateTimeInterface $now): bool
    {
        return $this->unencryptedToken->isExpired(now: $now);
    }

    public function isIdentifiedBy(string $id): bool
    {
        return $this->unencryptedToken->isIdentifiedBy(id: $id);
    }

    public function isMinimumTimeBefore(DateTimeInterface $now): bool
    {
        return $this->unencryptedToken->isMinimumTimeBefore(now: $now);
    }

    public function isPermittedFor(string $audience): bool
    {
        return $this->unencryptedToken->isPermittedFor(audience: $audience);
    }

    public function isRelatedTo(string $subject): bool
    {
        return $this->unencryptedToken->isRelatedTo(subject: $subject);
    }

    public function payload(): string
    {
        return $this->unencryptedToken->payload();
    }

    public function signature(): Signature
    {
        return $this->unencryptedToken->signature();
    }

    public function toString(): string
    {
        return $this->unencryptedToken->toString();
    }

    public function getClaim(string $claim): mixed
    {
        return $this->claims()->get(name: $claim);
    }
}
