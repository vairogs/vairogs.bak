<?php declare(strict_types = 1);

namespace Vairogs\Addon\Auth\OpenID\Steam\Model;

use Vairogs\Component\Auth\OpenID\Contracts\OpenIDUser;

class SteamUser implements OpenIDUser
{
    public const RETURNS_EMAIL = false;

    /**
     * @var string
     */
    protected string $openID;

    /**
     * @var int
     */
    protected int $communityState;

    /**
     * @var int
     */
    protected int $profileState;

    /**
     * @var string
     */
    protected string $persona;

    /**
     * @var int
     */
    protected int $commentPermission;

    /**
     * @var string
     */
    protected string $url;

    /**
     * @var array
     */
    protected array $avatar;

    /**
     * @var int
     */
    protected int $logoff;

    /**
     * @var int
     */
    protected int $personaState;

    /**
     * @var string|null
     */
    protected ?string $name;

    /**
     * @var int|null
     */
    protected ?int $clanId;

    /**
     * @var int
     */
    protected int $createdAt;

    /**
     * @var int
     */
    protected int $personaFlags;

    /**
     * @var string|null
     */
    protected ?string $countryCode;

    /**
     * @var int|null
     */
    protected ?int $stateCode;

    /**
     * @var string|null
     */
    protected ?string $playing;

    /**
     * @var int|null
     */
    protected ?int $playingId;

    /**
     * @return string|null
     */
    public function getPlaying(): ?string
    {
        return $this->playing;
    }

    /**
     * @param string|null $playing
     *
     * @return SteamUser
     */
    public function setPlaying(?string $playing): SteamUser
    {
        $this->playing = $playing;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getPlayingId(): ?int
    {
        return $this->playingId;
    }

    /**
     * @param int|null $playingId
     *
     * @return SteamUser
     */
    public function setPlayingId(?int $playingId): SteamUser
    {
        $this->playingId = $playingId;

        return $this;
    }

    /**
     * @return int
     */
    public function getCommunityState(): int
    {
        return $this->communityState;
    }

    /**
     * @param int $communityState
     *
     * @return SteamUser
     */
    public function setCommunityState(int $communityState): SteamUser
    {
        $this->communityState = $communityState;

        return $this;
    }

    /**
     * @return int
     */
    public function getProfileState(): int
    {
        return $this->profileState;
    }

    /**
     * @param int $profileState
     *
     * @return SteamUser
     */
    public function setProfileState(int $profileState): SteamUser
    {
        $this->profileState = $profileState;

        return $this;
    }

    /**
     * @return string
     */
    public function getPersona(): string
    {
        return $this->persona;
    }

    /**
     * @param string $persona
     *
     * @return SteamUser
     */
    public function setPersona(string $persona): SteamUser
    {
        $this->persona = $persona;

        return $this;
    }

    /**
     * @return int
     */
    public function getCommentPermission(): int
    {
        return $this->commentPermission;
    }

    /**
     * @param int $commentPermission
     *
     * @return SteamUser
     */
    public function setCommentPermission(int $commentPermission): SteamUser
    {
        $this->commentPermission = $commentPermission;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return SteamUser
     */
    public function setUrl(string $url): SteamUser
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return array
     */
    public function getAvatar(): array
    {
        return $this->avatar;
    }

    /**
     * @param array $avatar
     *
     * @return SteamUser
     */
    public function setAvatar(array $avatar): SteamUser
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @return int
     */
    public function getLogoff(): int
    {
        return $this->logoff;
    }

    /**
     * @param int $logoff
     *
     * @return SteamUser
     */
    public function setLogoff(int $logoff): SteamUser
    {
        $this->logoff = $logoff;

        return $this;
    }

    /**
     * @return int
     */
    public function getPersonaState(): int
    {
        return $this->personaState;
    }

    /**
     * @param int $personaState
     *
     * @return SteamUser
     */
    public function setPersonaState(int $personaState): SteamUser
    {
        $this->personaState = $personaState;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     *
     * @return SteamUser
     */
    public function setName(?string $name): SteamUser
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getClanId(): ?int
    {
        return $this->clanId;
    }

    /**
     * @param int|null $clanId
     *
     * @return SteamUser
     */
    public function setClanId(?int $clanId): SteamUser
    {
        $this->clanId = $clanId;

        return $this;
    }

    /**
     * @return int
     */
    public function getCreatedAt(): int
    {
        return $this->createdAt;
    }

    /**
     * @param int $createdAt
     *
     * @return SteamUser
     */
    public function setCreatedAt(int $createdAt): SteamUser
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return int
     */
    public function getPersonaFlags(): int
    {
        return $this->personaFlags;
    }

    /**
     * @param int $personaFlags
     *
     * @return SteamUser
     */
    public function setPersonaFlags(int $personaFlags): SteamUser
    {
        $this->personaFlags = $personaFlags;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    /**
     * @param string|null $countryCode
     *
     * @return SteamUser
     */
    public function setCountryCode(?string $countryCode): SteamUser
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getStateCode(): ?int
    {
        return $this->stateCode;
    }

    /**
     * @param int|null $stateCode
     *
     * @return SteamUser
     */
    public function setStateCode(?int $stateCode): SteamUser
    {
        $this->stateCode = $stateCode;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return null;
    }

    /**
     * @return string
     */
    public function getOpenID(): string
    {
        return $this->openID;
    }

    /**
     * @param string $openId
     *
     * @return SteamUser
     */
    public function setOpenID(string $openId): SteamUser
    {
        $this->openID = $openId;

        return $this;
    }

    /**
     * @return bool
     */
    public function returnsEmail(): bool
    {
        return self::RETURNS_EMAIL;
    }

    public function __toString()
    {
        return $this->getOpenID();
    }
}
