<?php declare(strict_types = 1);

namespace Vairogs\Addon\Auth\OpenID\Steam\Model\Traits;

trait SteamGetters
{
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
}
