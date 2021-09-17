<?php declare(strict_types = 1);

namespace Vairogs\Component\Auth\OpenIDConnect\Configuration;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken as BaseAccessToken;
use Psr\Http\Message\ResponseInterface;

abstract class Provider extends AbstractProvider
{
    public function getBaseAccessTokenUrl(array $params): string
    {
        return '';
    }

    public function getResourceOwnerDetailsUrl(BaseAccessToken $token): void
    {
    }

    public function getBaseAuthorizationUrl(): string
    {
        return '';
    }

    public function getDefaultScopes(): array
    {
        return [];
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
        return [];
    }
}