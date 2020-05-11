<?php declare(strict_types = 1);

namespace Vairogs\Addon\Auth\OpenID\Steam\Builder;

use Vairogs\Component\Auth\OpenID\Contracts\OpenIDUserBuilder;
use Vairogs\Component\Auth\OpenID\Contracts\OpenIDUser;
use Vairogs\Addon\Auth\OpenID\Steam\Model\SteamUser;
use Vairogs\Addon\Auth\OpenID\Steam\Model\SteamUserArrayFactory;

class SteamUserBuilder implements OpenIDUserBuilder
{
    /**
     * @param array $response
     *
     * @return OpenIDUser
     */
    public function getUser(array $response): OpenIDUser
    {
        return $this->getSteamUser($response);
    }

    /**
     * @param array $data
     *
     * @return SteamUser
     */
    protected function getSteamUser(array $data): SteamUser
    {
        return SteamUserArrayFactory::create($data['response']['players'][0]);
    }
}
