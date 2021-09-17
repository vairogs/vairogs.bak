<?php declare(strict_types = 1);

namespace Vairogs\Addon\Auth\OpenID\Steam\Model;

use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Stringable;
use Vairogs\Addon\Auth\OpenID\Steam\Contracts\User;
use Vairogs\Addon\Auth\OpenID\Steam\Model\Traits\SteamGetters;
use Vairogs\Addon\Auth\OpenID\Steam\Model\Traits\SteamSetters;
use Vairogs\Component\Auth\OpenID\Contracts\OpenIDUser;

/**
 * @ORM\MappedSuperclass()
 */
#[ORM\MappedSuperclass]
class Steam implements OpenIDUser, User, Stringable
{
    use SteamGetters;
    use SteamSetters;
    public const RETURNS_EMAIL = false;

    /**
     * @ORM\Column(unique=true)
     */
    #[ORM\Column(unique: true)]
    protected string $openID;

    /**
     * @ORM\Column
     */
    #[ORM\Column]
    protected int $communityState;

    /**
     * @ORM\Column
     */
    #[ORM\Column]
    protected int $profileState;

    /**
     * @ORM\Column
     */
    #[ORM\Column]
    protected string $persona;

    /**
     * @ORM\Column
     */
    #[ORM\Column]
    protected int $commentPermission;

    /**
     * @ORM\Column
     */
    #[ORM\Column]
    protected string $url;

    /**
     * @ORM\Column
     */
    #[ORM\Column]
    protected array $avatar;

    /**
     * @ORM\Column
     */
    #[ORM\Column]
    protected int $personaState;

    /**
     * @ORM\Column(nullable=true)
     */
    #[ORM\Column(nullable: true)]
    protected ?int $logoff = null;

    /**
     * @ORM\Column(nullable=true)
     */
    #[ORM\Column(nullable: true)]
    protected ?string $name = null;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    #[ORM\Column(type: 'bigint', nullable: true)]
    protected ?int $clanId = null;

    /**
     * @ORM\Column(nullable=true)
     */
    #[ORM\Column(nullable: true)]
    protected int $createdAt;

    /**
     * @ORM\Column(nullable=true)
     */
    #[ORM\Column(nullable: true)]
    protected ?int $personaFlags = null;

    /**
     * @ORM\Column(nullable=true)
     */
    #[ORM\Column(nullable: true)]
    protected ?string $countryCode = null;

    /**
     * @ORM\Column(nullable=true)
     */
    #[ORM\Column(nullable: true)]
    protected ?int $stateCode = null;

    /**
     * @ORM\Column(nullable=true)
     */
    #[ORM\Column(nullable: true)]
    protected ?string $playing = null;

    /**
     * @ORM\Column(nullable=true)
     */
    #[ORM\Column(nullable: true)]
    protected ?int $playingId = null;

    /**
     * @ORM\Column(nullable=true, unique=true)
     */
    #[ORM\Column(unique: true, nullable: true)]
    protected ?string $username = null;

    #[Pure]
    public function __toString(): string
    {
        return $this->openID;
    }

    public function returnsEmail(): bool
    {
        return self::RETURNS_EMAIL;
    }
}
