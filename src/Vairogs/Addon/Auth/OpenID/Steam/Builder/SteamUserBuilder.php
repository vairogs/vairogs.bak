<?php declare(strict_types = 1);

namespace Vairogs\Addon\Auth\OpenID\Steam\Builder;

use Vairogs\Addon\Auth\OpenID\Steam\Contracts\User;
use Vairogs\Addon\Auth\OpenID\Steam\Model\Steam;
use Vairogs\Addon\Auth\OpenID\Steam\UserArrayFactory;
use Vairogs\Auth\OpenID\Contracts\OpenIDUser;
use Vairogs\Auth\OpenID\Contracts\OpenIDUserBuilder;

use function rtrim;
use function str_starts_with;

class SteamUserBuilder implements OpenIDUserBuilder
{
    private const PROFILE_URL_START = 'https://steamcommunity.com/id/';
    protected string $userClass = Steam::class;

    public function getUser(array $response): OpenIDUser
    {
        return $this->getSteamUser(data: $response);
    }

    public function getUserClass(): string
    {
        return $this->userClass;
    }

    public function setUserClass(string $class): static
    {
        $this->userClass = $class;

        return $this;
    }

    private function getSteamUser(array $data): User
    {
        $user = (new UserArrayFactory())->create(user: new $this->userClass(), bag: $data['response']['players'][0]);

        return $user->setUsername(username: $this->getUsername(user: $user));
    }

    private function getUsername(User $user): string
    {
        if ($this->hasUsername(user: $user)) {
            $username = str_replace(search: self::PROFILE_URL_START, replace: '', subject: $user->getUrl());

            return rtrim(string: $username, characters: '/');
        }

        return $user->getOpenID();
    }

    private function hasUsername(User $user): bool
    {
        return str_starts_with(haystack: $user->getUrl(), needle: self::PROFILE_URL_START);
    }
}
