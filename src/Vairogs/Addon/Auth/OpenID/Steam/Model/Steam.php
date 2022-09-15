<?php declare(strict_types = 1);

namespace Vairogs\Addon\Auth\OpenID\Steam\Model;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Stringable;
use Vairogs\Addon\Auth\OpenID\Steam\Contracts\User;
use Vairogs\Auth\OpenID\Contracts\OpenIDUser;

#[ORM\MappedSuperclass]
class Steam implements OpenIDUser, User, Stringable
{
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

    public function getAvatar(): array
    {
        return $this->avatar;
    }

    public function getClanId(): int
    {
        return $this->clanId;
    }

    public function getCommentPermission(): int
    {
        return $this->commentPermission;
    }

    public function getCommunityState(): int
    {
        return $this->communityState;
    }

    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    public function getCreatedAt(): int
    {
        return $this->createdAt;
    }

    public function getEmail(): string
    {
        return '';
    }

    public function getLogoff(): int
    {
        return $this->logoff;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getOpenID(): string
    {
        return $this->openID;
    }

    public function getPersona(): string
    {
        return $this->persona;
    }

    public function getPersonaFlags(): int
    {
        return $this->personaFlags;
    }

    public function getPersonaState(): int
    {
        return $this->personaState;
    }

    public function getPlaying(): string
    {
        return $this->playing;
    }

    public function getPlayingId(): int
    {
        return $this->playingId;
    }

    public function getProfileState(): int
    {
        return $this->profileState;
    }

    public function getStateCode(): int
    {
        return $this->stateCode;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setAvatar(array $avatar): static
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function setClanId(int $clanId): static
    {
        $this->clanId = $clanId;

        return $this;
    }

    public function setCommentPermission(int $commentPermission): static
    {
        $this->commentPermission = $commentPermission;

        return $this;
    }

    public function setCommunityState(int $communityState): static
    {
        $this->communityState = $communityState;

        return $this;
    }

    public function setCountryCode(string $countryCode): static
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    public function setCreatedAt(int $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function setLogoff(int $logoff): static
    {
        $this->logoff = $logoff;

        return $this;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function setOpenID(string $openId): static
    {
        $this->openID = $openId;

        return $this;
    }

    public function setPersona(string $persona): static
    {
        $this->persona = $persona;

        return $this;
    }

    public function setPersonaFlags(int $personaFlags): static
    {
        $this->personaFlags = $personaFlags;

        return $this;
    }

    public function setPersonaState(int $personaState): static
    {
        $this->personaState = $personaState;

        return $this;
    }

    public function setPlaying(string $playing): static
    {
        $this->playing = $playing;

        return $this;
    }

    public function setPlayingId(int $playingId): static
    {
        $this->playingId = $playingId;

        return $this;
    }

    public function setProfileState(int $profileState): static
    {
        $this->profileState = $profileState;

        return $this;
    }

    public function setStateCode(int $stateCode): static
    {
        $this->stateCode = $stateCode;

        return $this;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }
}
