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

    public function build(array $uris, OpenIDConnectProvider $oidcProvider): self
    {
        foreach ($uris as $name => $uri) {
            $params = [
                'client_id' => $oidcProvider->getClientId(),
                'redirect_uri' => $oidcProvider->getRedirectUri(),
                'state' => $oidcProvider->getState(),
                'base_uri' => $oidcProvider->baseUri,
                'base_uri_post' => $oidcProvider->baseUriPost ?? $oidcProvider->baseUri,
            ];

            $this->uris[$name] = (new Uri(options: $uri, extra: $params, method: $uri['method'] ?? Request::METHOD_POST))
                ->setUseSession(useSession: $oidcProvider->useSession)
                ->setSession(session: $oidcProvider->session);
        }

        return $this;
    }
}
