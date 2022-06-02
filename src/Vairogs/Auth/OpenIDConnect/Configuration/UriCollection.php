<?php declare(strict_types = 1);

namespace Vairogs\Auth\OpenIDConnect\Configuration;

use Symfony\Component\HttpFoundation\Request;
use Vairogs\Auth\OpenIDConnect\OpenIDConnectProvider;

class UriCollection
{
    private array $uris = [];

    public function getUris(): array
    {
        return $this->uris;
    }

    public function addUri(Uri $uri, string $name): self
    {
        $this->uris[$name] = $uri;

        return $this;
    }

    public function getUri(string $name): ?Uri
    {
        return $this->uris[$name] ?? null;
    }

    public function build(array $uris, OpenIDConnectProvider $openIDConnectProvider): self
    {
        foreach ($uris as $name => $uri) {
            $params = [
                'client_id' => $openIDConnectProvider->getClientId(),
                'redirect_uri' => $openIDConnectProvider->getRedirectUri(),
                'state' => $openIDConnectProvider->getState(),
                'base_uri' => $openIDConnectProvider->getBaseUri(),
                'base_uri_post' => $openIDConnectProvider->getBaseUriPost() ?? $openIDConnectProvider->getBaseUri(),
            ];

            $this->uris[$name] = (new Uri(options: $uri, extra: $params, method: $uri['method'] ?? Request::METHOD_POST))
                ->setUseSession(useSession: $openIDConnectProvider->getUseSession())
                ->setSession(session: $openIDConnectProvider->getSession());
        }

        return $this;
    }
}
