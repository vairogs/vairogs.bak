<?php declare(strict_types = 1);

namespace Vairogs\Addon\Auth\OpenID\Steam\Model\Traits;

trait SteamSetters
{
    public function setAvatar(array $avatar): static
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function setClanId(?int $clanId): static
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

    public function setCountryCode(?string $countryCode): static
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    public function setCreatedAt(int $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function setLogoff(?int $logoff): static
    {
        $this->logoff = $logoff;

        return $this;
    }

    public function setName(?string $name): static
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

    public function setPersonaFlags(?int $personaFlags): static
    {
        $this->personaFlags = $personaFlags;

        return $this;
    }

    public function setPersonaState(int $personaState): static
    {
        $this->personaState = $personaState;

        return $this;
    }

    public function setPlaying(?string $playing): static
    {
        $this->playing = $playing;

        return $this;
    }

    public function setPlayingId(?int $playingId): static
    {
        $this->playingId = $playingId;

        return $this;
    }

    public function setProfileState(int $profileState): static
    {
        $this->profileState = $profileState;

        return $this;
    }

    public function setStateCode(?int $stateCode): static
    {
        $this->stateCode = $stateCode;

        return $this;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function setUsername(?string $username): static
    {
        $this->username = $username;

        return $this;
    }
}
