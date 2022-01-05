<?php declare(strict_types = 1);

namespace Vairogs\Auth\OpenIDConnect\Configuration;

use Lcobucci\JWT\Configuration;
use League\OAuth2\Client\Token\AccessToken;

class ParsedToken extends AccessToken
{
    protected ?IdToken $idToken;
    protected string $idTokenHint;

    public function __construct(array $options = [])
    {
        parent::__construct(options: $options);

        $parser = Configuration::forUnsecuredSigner()
            ->parser();

        if (!empty($this->values['id_token'])) {
            $this->idToken = new IdToken(token: $parser->parse(jwt: $this->values['id_token']));
            $this->idToken->setAccessTokenString(accessTokenString: $this->getToken());
            $this->idTokenHint = $this->values['id_token'];
            unset($this->values['id_token']);
        }
    }

    public function getIdToken(): IdToken
    {
        return $this->idToken;
    }

    public function jsonSerialize(): array
    {
        $parameters = parent::jsonSerialize();
        if ($this->idToken) {
            $parameters['id_token'] = $this->idToken->toString();
        }

        return $parameters;
    }

    public function getIdTokenHint(): string
    {
        return $this->idTokenHint;
    }
}
