<?php declare(strict_types = 1);

namespace Vairogs\Addon\Auth\OpenID\Steam\Model;

use Doctrine\ORM\Mapping as ORM;
use Vairogs\Addon\Auth\OpenID\Steam\Contracts\User;

/**
 * @ORM\MappedSuperclass()
 */
class SteamGifts extends Steam
{
    /**
     * @ORM\Column(type="string", nullable=true)
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
     * @return SteamGifts
     */
    public function setUsername(?string $username): User
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
