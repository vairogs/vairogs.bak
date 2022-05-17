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
use Vairogs\Extra\Constants\Definition;

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

    #[ORM\Column(type: Types::ARRAY)]
    protected array $avatar;

    #[ORM\Column(type: Types::INTEGER)]
    protected int $personaState;

    #[ORM\Column(type: Types::INTEGER, nullable: true, options: [Definition::DEFAULT => null])]
    protected ?int $logoff = null;

    #[ORM\Column(type: Types::STRING, nullable: true, options: [Definition::DEFAULT => null])]
    protected ?string $name = null;

    #[ORM\Column(type: Types::BIGINT, nullable: true, options: [Definition::DEFAULT => null])]
    protected ?int $clanId = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    protected int $createdAt;

    #[ORM\Column(type: Types::INTEGER, nullable: true, options: [Definition::DEFAULT => null])]
    protected ?int $personaFlags = null;

    #[ORM\Column(type: Types::STRING, nullable: true, options: [Definition::DEFAULT => null])]
    protected ?string $countryCode = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true, options: [Definition::DEFAULT => null])]
    protected ?int $stateCode = null;

    #[ORM\Column(type: Types::STRING, nullable: true, options: [Definition::DEFAULT => null])]
    protected ?string $playing = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true, options: [Definition::DEFAULT => null])]
    protected ?int $playingId = null;

    #[ORM\Column(type: Types::STRING, unique: true, nullable: true, options: [Definition::DEFAULT => null])]
    protected ?string $username = null;

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
