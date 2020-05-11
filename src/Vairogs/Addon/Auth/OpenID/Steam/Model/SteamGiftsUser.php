<?php declare(strict_types = 1);

namespace Vairogs\Addon\Auth\OpenID\Steam\Model;

class SteamGiftsUser extends SteamUser
{
    /**
     * @var string|null
     */
    protected ?string $username;

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string|null $username
     *
     * @return SteamGiftsUser
     */
    public function setUsername(?string $username): SteamGiftsUser
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getUsername() ?? $this->getOpenID();
    }
}
