<?php declare(strict_types = 1);

namespace Vairogs\Auth\OpenIDConnect\Utils\Traits;

use Lcobucci\JWT\Signer;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Vairogs\Auth\OpenIDConnect\Configuration\Uri;
use Vairogs\Auth\OpenIDConnect\Configuration\UriCollection;
use Vairogs\Auth\OpenIDConnect\Configuration\ValidatorChain;

trait OpenIDConnectProviderVariables
{
    protected UriCollection $uriCollection;
    protected string $publicKey;
    protected Signer $signer;
    protected ValidatorChain $validatorChain;
    protected string $idTokenIssuer;
    protected bool $useSession = false;
    protected ?SessionInterface $session = null;
    protected int $statusCode;
    protected string $baseUri;
    protected ?string $baseUriPost;
    protected bool $verify = true;

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

    public function setUseSession(bool $useSession): static
    {
        $this->useSession = $useSession;

        return $this;
    }

    public function getSession(): ?SessionInterface
    {
        return $this->session;
    }

    public function setSession(?SessionInterface $session): static
    {
        $this->session = $session;

        return $this;
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

    public function setBaseUriPost(?string $baseUriPost): static
    {
        $this->baseUriPost = $baseUriPost;

        return $this;
    }

    public function getUriCollection(): UriCollection
    {
        return $this->uriCollection;
    }

    public function setUriCollection(UriCollection $uriCollection): static
    {
        $this->uriCollection = $uriCollection;

        return $this;
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
        return $this->signer;
    }

    public function setSigner(Signer $signer = new Sha256()): static
    {
        $this->signer = $signer;

        return $this;
    }

    public function getVerify(): bool
    {
        return $this->verify;
    }

    public function setVerify(bool $verify): static
    {
        $this->verify = $verify;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValidatorChain(): ValidatorChain
    {
        return $this->validatorChain;
    }

    public function setValidatorChain(ValidatorChain $validatorChain = new ValidatorChain()): static
    {
        $this->validatorChain = $validatorChain;

        return $this;
    }

    public function getUri(string $name): ?Uri
    {
        return $this->uriCollection->getUri(name: $name);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode): static
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function setIdTokenIssuer(string $idTokenIssuer): static
    {
        $this->idTokenIssuer = $idTokenIssuer;

        return $this;
    }

    protected function getIdTokenIssuer(): string
    {
        return $this->idTokenIssuer;
    }
}
