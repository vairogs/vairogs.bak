<?php declare(strict_types = 1);

namespace Vairogs\Auth\OpenIDConnect\Utils\Traits;

use Lcobucci\JWT\Signer;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Vairogs\Auth\OpenIDConnect\Configuration\Uri;
use Vairogs\Auth\OpenIDConnect\Configuration\UriCollection;
use Vairogs\Auth\OpenIDConnect\Configuration\ValidatorChain;

trait OpenIDConnectProviderVariables
{
    protected ?Signer $signer = null;
    protected ?string $baseUriPost;
    protected RequestStack $requestStack;
    protected UriCollection $uriCollection;
    protected ValidatorChain $validatorChain;
    protected bool $useSession = false;
    protected bool $verify = true;
    protected int $statusCode;
    protected string $baseUri;
    protected string $idTokenIssuer;
    protected string $publicKey;

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function getRedirectUri(): string
    {
        return $this->redirectUri;
    }

    public function getUseSession(): bool
    {
        return $this->useSession;
    }

    public function getSession(): ?SessionInterface
    {
        return $this->requestStack->getCurrentRequest()?->getSession();
    }

    public function getBaseUri(): string
    {
        return $this->baseUri;
    }

    public function setBaseUri(string $baseUri): static
    {
        $this->baseUri = $baseUri;

        return $this;
    }

    public function getBaseUriPost(): ?string
    {
        return $this->baseUriPost;
    }

    public function getUriCollection(): UriCollection
    {
        return $this->uriCollection;
    }

    public function setPublicKey(string $publicKey): static
    {
        $this->publicKey = $publicKey;

        return $this;
    }

    public function getRouter(): RouterInterface
    {
        return $this->router;
    }

    public function getSigner(): Signer
    {
        return $this->signer ?? new Sha256();
    }

    public function getVerify(): bool
    {
        return $this->verify;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValidatorChain(): ValidatorChain
    {
        return $this->validatorChain;
    }

    public function getUri(string $name): ?Uri
    {
        return $this->uriCollection->getUri(name: $name);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    protected function getIdTokenIssuer(): string
    {
        return $this->idTokenIssuer;
    }
}
