<?php declare(strict_types = 1);

namespace Vairogs\Component\Auth\OpenIDConnect;

use ErrorException;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use JsonException;
use Lcobucci\JWT\Signer;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use League\OAuth2\Client\Grant\AbstractGrant;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken as BaseAccessToken;
use League\OAuth2\Client\Token\AccessTokenInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use UnexpectedValueException;
use Vairogs\Component\Auth\OpenIDConnect\Configuration\AccessToken;
use Vairogs\Component\Auth\OpenIDConnect\Configuration\Uri;
use Vairogs\Component\Auth\OpenIDConnect\Configuration\ValidatorChain;
use Vairogs\Component\Utils\Helper\Identification;
use Vairogs\Component\Utils\Helper\Json;
use Vairogs\Extra\Specification;
use function array_merge;
use function base64_encode;
use function is_array;
use function json_last_error;
use function sprintf;

abstract class OpenIDConnectProvider extends AbstractProvider
{
    /**
     * @var Uri[]
     */
    protected array $uris = [];
    protected string $publicKey;
    protected Signer $signer;
    protected ValidatorChain $validatorChain;
    protected string $idTokenIssuer;
    protected bool $useSession = false;
    protected SessionInterface $session;
    protected int $statusCode;
    protected string $baseUri;

    public function __construct(array $options = [], array $collaborators = [], protected ?Router $router = null, ?RequestStack $requestStack = null)
    {
        $this->signer = new Signer\Rsa\Sha256();
        $this->validatorChain = new ValidatorChain();
        $this->setValidators();
        $this->session = $requestStack->getCurrentRequest()
            ->getSession();
        parent::__construct($options, $collaborators);
        $this->buildParams($options);
    }

    private function setValidators(): void
    {
        $this->validatorChain->setValidators([
            new Specification\NotEmpty('iat', true),
            new Specification\GreaterOrEqualsTo('exp', true),
            new Specification\EqualsTo('iss', true),
            new Specification\EqualsTo('aud', true),
            new Specification\NotEmpty('sub', true),
            new Specification\LesserOrEqualsTo('nbf'),
            new Specification\EqualsTo('jti'),
            new Specification\EqualsTo('azp'),
            new Specification\EqualsTo('nonce'),
        ]);
    }

    private function buildParams(array $options = []): void
    {
        if ([] !== $options) {
            $this->clientId = $options['client_key'];
            $this->clientSecret = $options['client_secret'];
            unset($options['client_secret'], $options['client_key']);
            $this->idTokenIssuer = $options['id_token_issuer'];
            $this->publicKey = 'file://' . $options['public_key'];
            $this->state = $this->getRandomState();
            $this->baseUri = $options['base_uri'];
            $this->useSession = $options['use_session'];
            $params = !empty($options['redirect']['params']) ? $options['redirect']['params'] : [];
            $url = match ($options['redirect']['type']) {
                'uri' => $options['redirect']['uri'],
                'route' => $this->router->generate($options['redirect']['route'], $params, UrlGeneratorInterface::ABSOLUTE_URL),
                default => null,
            };
            $this->redirectUri = $url;

            $this->buildUris($options);
        }
    }

    protected function getRandomState($length = 32): string
    {
        return Identification::getUniqueId($length);
    }

    /**
     * @throws ErrorException
     * @throws IdentityProviderException
     */
    public function getAccessToken($grant, array $options = []): AccessTokenInterface|BaseAccessToken
    {
        /** @var AccessToken $accessToken */
        $accessToken = $this->getAccessTokenFunction($grant, $options);

        if (null === $accessToken) {
            throw new ErrorException('Invalid access token.');
        }

        $token = $accessToken->getIdToken();
        // id_token is empty.
        if (null === $token) {
            throw new ErrorException('Expected an id_token but did not receive one from the authorization server');
        }

        try {
            (new SignedWith($this->signer, $this->getPublicKey()))->assert($token);
        } catch (Exception) {
            throw new ErrorException('Received an invalid id_token from authorization server');
        }

        $currentTime = time();
        $data = [
            'iss' => $this->getIdTokenIssuer(),
            'exp' => $currentTime,
            'auth_time' => $currentTime,
            'iat' => $currentTime,
            'nbf' => $currentTime,
            'aud' => $this->clientId,
        ];

        if ($token->hasClaim('azp')) {
            $data['azp'] = $this->clientId;
        }

        if (false === $this->validatorChain->validate($data, $token)) {
            throw new ErrorException('The id_token did not pass validation.');
        }

        $this->saveSession($accessToken);

        return $accessToken;
    }

    private function buildUris($options = []): void
    {
        foreach ($options['uris'] as $name => $uri) {
            $opt = [
                'client_id' => $this->clientId,
                'redirect_uri' => $this->redirectUri,
                'state' => $this->state,
                'base_uri' => $this->baseUri,
            ];
            $method = $uri['method'] ?? Request::METHOD_POST;
            $this->uris[$name] = new Uri($uri, $opt, $this->useSession, $method, $this->session);
        }
    }

    public function getResourceOwnerDetailsUrl(BaseAccessToken $token): void
    {
    }

    private function saveSession($accessToken): void
    {
        if ($this->useSession) {
            $this->session->set('access_token', $accessToken->getToken());
            $this->session->set('refresh_token', $accessToken->getRefreshToken());
            $this->session->set('id_token', $accessToken->getIdTokenHint());
        }
    }

    public function getValidatorChain(): ValidatorChain
    {
        return $this->validatorChain;
    }

    public function getUri($name): ?Uri
    {
        return $this->uris[$name];
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

    /**
     * @throws IdentityProviderException
     */
    public function getRefreshToken($token, array $options = []): array|string|ResponseInterface
    {
        $params = [
            'token' => $token,
            'grant_type' => 'refresh_token',
        ];
        $params = array_merge($params, $options);
        $request = $this->getRefreshTokenRequest($params);

        return $this->getResponse($request);
    }

    public function check(mixed $response = null): bool
    {
        return null !== $response;
    }

    protected function getRefreshTokenRequest(array $params): RequestInterface
    {
        $method = $this->getAccessTokenMethod();
        $url = $this->getRefreshTokenUrl();
        $options = $this->getAccessTokenOptions($params);

        return $this->getRequest($method, $url, $options);
    }

    abstract public function getRefreshTokenUrl(): string;

    #[ArrayShape([
        'headers' => 'string[]',
        'body' => 'mixed',
    ])]
    protected function getAccessTokenOptions(array $params): array
    {
        $options = $this->getBaseTokenOptions($params);
        $options['headers']['authorization'] = 'Basic: ' . base64_encode($this->clientId . ':' . $this->clientSecret);

        return $options;
    }

    #[ArrayShape([
        'headers' => 'string[]',
        'body' => 'mixed',
    ])]
    protected function getBaseTokenOptions(array $params): array
    {
        $options = [
            'headers' => [
                'content-type' => 'application/x-www-form-urlencoded',
            ],
        ];
        if (self::METHOD_POST === $this->getAccessTokenMethod()) {
            $options['body'] = $this->getAccessTokenBody($params);
        }

        return $options;
    }

    #[Pure]
    protected function getAccessTokenBody(array $params): string
    {
        return $this->buildQueryString($params);
    }

    /**
     * @throws IdentityProviderException
     */
    public function getValidateToken($token, array $options = []): array|string|ResponseInterface
    {
        $params = [
            'token' => $token,
        ];
        $params = array_merge($params, $options);
        $request = $this->getValidateTokenRequest($params);

        return $this->getResponse($request);
    }

    protected function getValidateTokenRequest(array $params): RequestInterface
    {
        $method = $this->getAccessTokenMethod();
        $url = $this->getValidateTokenUrl();
        $options = $this->getBaseTokenOptions($params);

        return $this->getRequest($method, $url, $options);
    }

    abstract public function getValidateTokenUrl(): string;

    /**
     * @throws IdentityProviderException
     */
    public function getRevokeToken($token, array $options = []): array|string|ResponseInterface
    {
        $params = [
            'token' => $token,
        ];
        $params = array_merge($params, $options);
        $request = $this->getRevokeTokenRequest($params);

        return $this->getResponse($request);
    }

    protected function checkResponse(ResponseInterface $response, $data): void
    {
    }

    protected function getRevokeTokenRequest(array $params): RequestInterface
    {
        $method = $this->getAccessTokenMethod();
        $url = $this->getRevokeTokenUrl();
        $options = $this->getAccessTokenOptions($params);

        return $this->getRequest($method, $url, $options);
    }

    abstract public function getRevokeTokenUrl(): string;

    protected function createResourceOwner(array $response, BaseAccessToken $token): array
    {
        return [];
    }

    protected function getRequiredOptions(): array
    {
        return [];
    }

    public function getBaseAccessTokenUrl(array $params): string
    {
        return '';
    }

    public function getBaseAuthorizationUrl(): string
    {
        return '';
    }

    public function getDefaultScopes(): array
    {
        return [];
    }

    protected function getScopeSeparator(): string
    {
        return ' ';
    }

    protected function getIdTokenIssuer(): string
    {
        return $this->idTokenIssuer;
    }

    /**
     * @throws IdentityProviderException
     * @throws ErrorException
     */
    public function getAccessTokenFunction($grant, array $options = []): ?AccessToken
    {
        $grant = $this->verifyGrant($grant);

        $params = [
            'redirect_uri' => $this->redirectUri,
        ];

        $params = $grant->prepareRequestParameters($params, $options);
        $request = $this->getAccessTokenRequest($params);
        $response = $this->getResponse($request);
        if (!is_array($response)) {
            throw new ErrorException('Invalid request parameters');
        }
        $prepared = $this->prepareAccessTokenResponse($response);

        return $this->createAccessToken($prepared, $grant);
    }

    /**
     * @throws IdentityProviderException
     */
    public function getResponse(RequestInterface $request): array|string|ResponseInterface
    {
        $response = $this->getResponse($request);
        $this->statusCode = $response->getStatusCode();
        $parsed = $this->parseResponse($response);
        $this->checkResponse($response, $parsed);

        return $parsed;
    }

    protected function createAccessToken(array $response, ?AbstractGrant $grant = null): ?AccessToken
    {
        if ($this->check($response)) {
            return new AccessToken($response);
        }

        return null;
    }

    public function getPublicKey(): Key
    {
        return Key\InMemory::plainText($this->publicKey);
    }

    protected function getAllowedClientOptions(array $options): array
    {
        return [
            'timeout',
            'proxy',
            'verify',
        ];
    }

    /**
     * @throws JsonException
     */
    protected function parseJson($content): array
    {
        if (empty($content)) {
            return [];
        }

        $content = Json::decode($content, 1);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new UnexpectedValueException(sprintf('Failed to parse JSON response: %s', json_last_error_msg()));
        }

        return $content;
    }
}
