<?php declare(strict_types = 1);

namespace Vairogs\Addon\Auth\OpenID\Steam\Model;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Stringable;
use Vairogs\Addon\Auth\OpenID\Steam\Contracts\User;
use Vairogs\Addon\Auth\OpenID\Steam\Model\Traits\SteamGetters;
use Vairogs\Addon\Auth\OpenID\Steam\Model\Traits\SteamSetters;
use Vairogs\Auth\OpenID\Contracts\OpenIDUser;

#[ORM\MappedSuperclass]
class Steam implements OpenIDUser, User, Stringable
{
    use SteamGetters;
    use SteamSetters;

    #[ORM\Column(type: Types::STRING, unique: true)]
    protected string $openID;

    #[ORM\Column(type: Types::INTEGER)]
    protected int $communityState;

    #[ORM\Column(type: Types::INTEGER)]
    protected int $profileState;

    #[ORM\Column(type: Types::STRING)]
    protected string $persona;

    #[ORM\Column(type: Types::INTEGER)]
    protected int $commentPermission;

    #[ORM\Column(type: Types::STRING)]
    protected string $url;

    #[ORM\Column(type: Types::JSON)]
    protected array $avatar;

    #[ORM\Column(type: Types::INTEGER)]
    protected int $personaState;

    #[ORM\Column(type: Types::INTEGER)]
    protected int $logoff;

    #[ORM\Column(type: Types::STRING)]
    protected string $name;

    #[ORM\Column(type: Types::BIGINT)]
    protected int $clanId;

    #[ORM\Column(type: Types::INTEGER)]
    protected int $createdAt;

    #[ORM\Column(type: Types::INTEGER)]
    protected int $personaFlags;

    #[ORM\Column(type: Types::STRING)]
    protected string $countryCode;

    #[ORM\Column(type: Types::INTEGER)]
    protected int $stateCode;

    #[ORM\Column(type: Types::STRING)]
    protected string $playing;

    #[ORM\Column(type: Types::INTEGER)]
    protected int $playingId;

    #[ORM\Column(type: Types::STRING, unique: true)]
    protected string $username;

    #[Pure]
    public function __toString(): string
    {
        return $this->openID;
    }

    public function returnsEmail(): bool
    {
        return false;
    }
}
