<?php declare(strict_types = 1);

namespace Vairogs\Addon\Auth\OpenID\Steam\Builder;

use Vairogs\Component\Auth\OpenID\Contracts\OpenIDUserBuilder;
use Vairogs\Component\Auth\OpenID\Contracts\OpenIDUser;
use Vairogs\Addon\Auth\OpenID\Steam\Model\Steam;
use Vairogs\Addon\Auth\OpenID\Steam\Contracts\User;
use Vairogs\Addon\Auth\OpenID\Steam\UserArrayFactory;

class SteamUserBuilder implements OpenIDUserBuilder
{
    /**
     * @var string
     */
    protected string $userClass = Steam::class;

    /**
     * @param string $userClass
     * @return SteamUserBuilder
     */
    public function setUserClass(string $userClass): SteamUserBuilder
    {
        $this->userClass = $userClass;

        return $this;
    }

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
     * @return Steam
     */
    protected function getSteamUser(array $data): User
    {
        return UserArrayFactory::create(new $this->userClass(), $data['response']['players'][0]);
    }
}
