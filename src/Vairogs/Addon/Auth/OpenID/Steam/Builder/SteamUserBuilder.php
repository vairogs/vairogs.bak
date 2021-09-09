<?php declare(strict_types = 1);

namespace Vairogs\Addon\Auth\OpenID\Steam\Builder;

use Vairogs\Addon\Auth\OpenID\Steam\Contracts\User;
use Vairogs\Addon\Auth\OpenID\Steam\Model\Steam;
use Vairogs\Addon\Auth\OpenID\Steam\UserArrayFactory;
use Vairogs\Component\Auth\OpenID\Contracts\OpenIDUser;
use Vairogs\Component\Auth\OpenID\Contracts\OpenIDUserBuilder;
use function rtrim;
use function str_starts_with;

class SteamUserBuilder implements OpenIDUserBuilder
{
    private const PROFILE_URL_START = 'https://steamcommunity.com/id/';
    public const USER_CLASS = Steam::class;

    protected string $userClass = self::USER_CLASS;

    public function getUser(array $response): OpenIDUser
    {
        return $this->getSteamUser($response);
    }

    private function getSteamUser(array $data): User
    {
        $user = UserArrayFactory::create(new $this->userClass(), $data['response']['players'][0]);

        return $user->setUsername($this->getUsername($user));
    }

    private function getUsername(User $user): string
    {
        if ($this->hasUsername($user)) {
            $username = str_replace(self::PROFILE_URL_START, '', $user->getUrl());

            return rtrim($username, '/');
        }

        return $user->getOpenID();
    }

    private function hasUsername(User $user): bool
    {
        return str_starts_with($user->getUrl(), self::PROFILE_URL_START);
    }

    public function setUserClass(string $class): static
    {
        $this->userClass = $class;

        return $this;
    }
}
