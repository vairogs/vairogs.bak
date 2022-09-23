<?php declare(strict_types = 1);

namespace Vairogs\Auth\OpenIDConnect\Configuration;

use Exception;
use League\OAuth2\Client\Grant\AbstractGrant;
use League\OAuth2\Client\Provider\AbstractProvider as BaseProvider;
use League\OAuth2\Client\Provider\GenericResourceOwner;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessToken as BaseAccessToken;
use Psr\Http\Message\ResponseInterface;
use UnexpectedValueException;
use Vairogs\Utils\Helper\Json;

use function sprintf;

abstract class AbstractProvider extends BaseProvider
{
    protected array $scopes = [];
    protected array $options = [];
    protected string $baseAccessTokenUrl = '';
    protected string $baseAuthorizationUrl = '';

    public function getBaseAccessTokenUrl(array $params): string
    {
        return $this->baseAccessTokenUrl;
    }

    public function getResourceOwnerDetailsUrl(BaseAccessToken $token): string
    {
        return '';
    }

    public function getBaseAuthorizationUrl(): string
    {
        return $this->baseAuthorizationUrl;
    }

    public function getDefaultScopes(): array
    {
        return $this->scopes;
    }

    abstract public function getRefreshTokenUrl(): string;

    abstract public function getValidateTokenUrl(): string;

    abstract public function getRevokeTokenUrl(): string;

    public function check(mixed $response = null): bool
    {
        return null !== $response;
    }

    protected function createResourceOwner(array $response, BaseAccessToken $token): ResourceOwnerInterface
    {
        return new GenericResourceOwner($response, $token->getToken());
    }

    protected function getScopeSeparator(): string
    {
        return ' ';
    }

    protected function checkResponse(ResponseInterface $response, $data): void
    {
        // Override parent method
    }

    protected function getRequiredOptions(): array
    {
        return $this->options;
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
     * @throws UnexpectedValueException
     */
    protected function parseJson($content): array
    {
        if ('' === $content) {
            return [];
        }

        try {
            return (new Json())->decode(json: $content, flags: Json::ASSOCIATIVE);
        } catch (Exception $exception) {
            throw new UnexpectedValueException(message: sprintf('Failed to parse JSON response: %s', $exception->getMessage()), previous: $exception);
        }
    }

    protected function createAccessToken(array $response, ?AbstractGrant $grant = null): ?ParsedToken
    {
        if ($this->check(response: $response)) {
            return new ParsedToken(options: $response);
        }

        return null;
    }
}
