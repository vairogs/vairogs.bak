<?php declare(strict_types = 1);

namespace Vairogs\Auth\OpenIDConnect\Configuration;

use League\OAuth2\Client\Provider\AbstractProvider as BaseProvider;
use League\OAuth2\Client\Token\AccessToken as BaseAccessToken;
use Psr\Http\Message\ResponseInterface;

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

    public function getResourceOwnerDetailsUrl(BaseAccessToken $token): void
    {
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

    protected function createResourceOwner(array $response, BaseAccessToken $token): array
    {
        return [];
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
}
