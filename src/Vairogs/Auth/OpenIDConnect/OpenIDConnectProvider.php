<?php declare(strict_types = 1);

namespace Vairogs\Auth\OpenIDConnect;

use DateTimeImmutable;
use InvalidArgumentException;
use JetBrains\PhpStorm\ArrayShape;
use Lcobucci\JWT\Signer;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Token\RegisteredClaims;
use League\OAuth2\Client\Grant\AbstractGrant;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken as BaseAccessToken;
use League\OAuth2\Client\Token\AccessTokenInterface;
use Psr\Http\Message\RequestInterface;
use SimpleToImplement\DependencyInjection\Functions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Vairogs\Auth\OpenIDConnect\Configuration\AbstractProvider;
use Vairogs\Auth\OpenIDConnect\Configuration\Constraint\Constraint;
use Vairogs\Auth\OpenIDConnect\Configuration\Constraint\Equal;
use Vairogs\Auth\OpenIDConnect\Configuration\Constraint\GreaterOrEqual;
use Vairogs\Auth\OpenIDConnect\Configuration\Constraint\Hashed;
use Vairogs\Auth\OpenIDConnect\Configuration\Constraint\IssuedBy;
use Vairogs\Auth\OpenIDConnect\Configuration\Constraint\LesserOrEqual;
use Vairogs\Auth\OpenIDConnect\Configuration\Constraint\SignedWith;
use Vairogs\Auth\OpenIDConnect\Configuration\ParsedToken;
use Vairogs\Auth\OpenIDConnect\Configuration\UriCollection;
use Vairogs\Auth\OpenIDConnect\Configuration\ValidatorChain;
use Vairogs\Auth\OpenIDConnect\Exception\OpenIDConnectException;
use Vairogs\Auth\OpenIDConnect\Utils\Constants\Enum\Redirect;
use Vairogs\Core\Registry\HasRegistry;
use Vairogs\Extra\Constants\ContentType;
use Vairogs\Utils\Helper\Char;
use Vairogs\Utils\Helper\Identification;

use function array_merge;
use function base64_encode;
use function is_array;
use function property_exists;
use function sprintf;

abstract class OpenIDConnectProvider extends AbstractProvider implements HasRegistry
{
    protected ?string $baseUriPost = null;
    protected bool $useSession = false;
    protected bool $verify = true;
    protected string $baseUri;
    protected ?SessionInterface $session = null;
    protected Signer $signer;
    protected UriCollection $uriCollection;
    protected ValidatorChain $validatorChain;
    protected int $statusCode;
    protected string $idTokenIssuer;

    /** @var non-empty-string */
    protected string $publicKey;

    public function __construct(protected string $name, protected readonly RouterInterface $router, protected RequestStack $requestStack, array $options = [], array $collaborators = [])
    {
        parent::__construct(options: $options, collaborators: $collaborators);

        $this->state = (new Identification())->getUniqueId();
        if ([] !== $options) {
            $this->configure(options: $options);
        }

        $this->signer ??= new Sha256();
        $this->session = $this->requestStack->getCurrentRequest()?->getSession();
    }

    public function getBaseUriPost(): ?string
    {
        return $this->baseUriPost;
    }

    public function getUseSession(): bool
    {
        return $this->useSession;
    }

    public function getVerify(): bool
    {
        return $this->verify;
    }

    public function getBaseUri(): string
    {
        return $this->baseUri;
    }

    public function getSession(): ?SessionInterface
    {
        return $this->session;
    }

    public function setSigner(Signer $signer): static
    {
        $this->signer = $signer;

        return $this;
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function getRedirectUri(): string
    {
        return $this->redirectUri;
    }

    /** @param non-empty-string $publicKey */
    public function setPublicKey(string $publicKey): static
    {
        $this->publicKey = $publicKey;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUriCollection(): UriCollection
    {
        return $this->uriCollection;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @throws IdentityProviderException
     */
    public function getAccessToken($grant, array $options = []): AccessTokenInterface|BaseAccessToken
    {
        $accessToken = $this->getAccessTokenFunction(grant: $grant, options: $options);

        if (!$accessToken instanceof ParsedToken || null === $idToken = $accessToken->getIdToken()) {
            throw new OpenIDConnectException(message: 'Expected an id_token but did not receive one from the authorization server');
        }

        $this->setValidators();
        $this->validatorChain->validate(token: $idToken);

        $this->saveSession(accessToken: $accessToken);

        return $accessToken;
    }

    /**
     * @throws IdentityProviderException
     */
    public function getRefreshToken($token, array $options = []): array
    {
        return $this->getTokenResponse(request: $this->getTokenRequest(params: array_merge(['token' => $token, 'grant_type' => 'refresh_token'], $options), url: $this->getRefreshTokenUrl()));
    }

    /**
     * @throws IdentityProviderException
     */
    public function getAccessTokenFunction(?AbstractGrant $grant, array $options = []): ?ParsedToken
    {
        $grant = $this->verifyGrant(grant: $grant);

        $params = $grant->prepareRequestParameters(defaults: ['redirect_uri' => $this->redirectUri], options: $options);
        $request = $this->getAccessTokenRequest(params: $params);
        $response = $this->getTokenResponse(request: $request);
        $prepared = $this->prepareAccessTokenResponse(result: $response);

        return $this->createAccessToken(response: $prepared, grant: $grant);
    }

    public function getPublicKey(): Key
    {
        return InMemory::plainText(contents: $this->publicKey);
    }

    /**
     * @throws IdentityProviderException
     */
    public function getValidateToken($token, array $options = []): array
    {
        return $this->getTokenResponse(request: $this->getTokenRequest(params: array_merge(['token' => $token], $options), url: $this->getValidateTokenUrl()));
    }

    /**
     * @throws IdentityProviderException
     */
    public function getTokenResponse(RequestInterface $request): array
    {
        $response = $this->getResponse(request: $request);
        $this->statusCode = $response->getStatusCode();
        $parsed = $this->parseResponse(response: $response);

        if (!is_array($parsed)) {
            return [];
        }

        $this->checkResponse(response: $response, data: $parsed);

        return $parsed;
    }

    /**
     * @throws IdentityProviderException
     */
    public function getRevokeToken($token, array $options = []): array
    {
        return $this->getTokenResponse(request: $this->getTokenRequest(params: array_merge(['token' => $token], $options), url: $this->getRevokeTokenUrl()));
    }

    protected function getIdTokenIssuer(): string
    {
        return $this->idTokenIssuer;
    }

    protected function configure(array $options = []): void
    {
        $this->redirectUri = match ($options['redirect']['type']) {
            Redirect::ROUTE->value => $this->router->generate(name: $options['redirect']['route'], parameters: $options['redirect']['params'] ?? [], referenceType: UrlGeneratorInterface::ABSOLUTE_URL),
            Redirect::URI->value => $options['redirect']['uri'],
            default => throw new InvalidArgumentException(message: sprintf('Type must be % or %s', Redirect::ROUTE->value, Redirect::URI->value)),
        };

        $uris = $options['uris'] ?? [];
        unset($options['redirect'], $options['uris']);

        foreach ((new Functions())->makeOneDimension(array: $options, maxDepth: 0) as $key => $value) {
            if (property_exists(object_or_class: $this, property: $var = (new Char())->toCamelCase(string: $key))) {
                $this->{$var} = $value;
            }
        }

        $this->setPublicKey(publicKey: 'file://' . $this->publicKey);
        $this->uriCollection = (new UriCollection())->build(uris: $uris, oidcProvider: $this);
    }

    protected function getTokenRequest(array $params, string $url): RequestInterface
    {
        return $this->getRequest(method: Request::METHOD_POST, url: $url, options: $this->getAccessTokenOptions(params: $params));
    }

    protected function setValidators(): void
    {
        $this->validatorChain
            ->setAssertions(assertions: [
                (new Equal(expected: $this->clientId))->setClaim(claim: 'azp'),
                (new Equal(expected: [$this->clientId]))->setClaim(claim: RegisteredClaims::AUDIENCE),
                (new Constraint())->setClaim(claim: RegisteredClaims::ISSUED_AT)->setRequired(required: true),
                (new Constraint())->setClaim(claim: RegisteredClaims::SUBJECT)->setRequired(required: true),
                (new GreaterOrEqual(expected: (new DateTimeImmutable())->getTimestamp()))->setClaim(claim: RegisteredClaims::EXPIRATION_TIME)->setRequired(required: true),
                (new Hashed())->setClaim(claim: 'at_hash')->setRequired(required: true),
                (new IssuedBy(issuers: $this->getIdTokenIssuer()))->setRequired(required: true),
                (new LesserOrEqual(expected: (new DateTimeImmutable())->getTimestamp()))->setClaim(claim: RegisteredClaims::NOT_BEFORE),
                (new SignedWith(signer: $this->signer, key: $this->getPublicKey()))->setRequired(required: true),
            ]);
    }

    #[ArrayShape([
        'headers' => 'string[]',
        'body' => 'mixed',
    ])]
    protected function getAccessTokenOptions(array $params): array
    {
        $options = $this->getBaseTokenOptions(params: $params);
        $options['headers']['authorization'] = 'Basic ' . base64_encode(string: $this->clientId . ':' . $this->clientSecret);

        return $options;
    }

    #[ArrayShape([
        'headers' => 'string[]',
        'body' => 'mixed',
    ])]
    protected function getBaseTokenOptions(array $params): array
    {
        return [
            'headers' => ['content-type' => ContentType::X_WWW_FORM_URLENCODED, ],
            'body' => $this->buildQueryString(params: $params),
        ];
    }

    protected function saveSession(ParsedToken $accessToken): void
    {
        $session = $this->requestStack->getCurrentRequest()?->getSession();
        if ($this->useSession && null !== $session) {
            $session->set(name: 'access_token', value: $accessToken->getToken());
            $session->set(name: 'id_token', value: $accessToken->getIdTokenHint());
            $session->set(name: 'refresh_token', value: $accessToken->getRefreshToken());
        }
    }

    protected function getAccessTokenRequest(array $params): RequestInterface
    {
        return $this->getRequest(method: Request::METHOD_POST, url: $this->getAccessTokenUrl(params: $params), options: $this->getAccessTokenOptions(params: $params));
    }
}
