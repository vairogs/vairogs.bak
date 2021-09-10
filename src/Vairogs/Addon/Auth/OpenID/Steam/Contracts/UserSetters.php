<?php declare(strict_types = 1);

namespace Vairogs\Addon\Auth\OpenID\Steam\Contracts;

interface UserSetters
{
    public function setPlaying(?string $playing): static;

    public function setPlayingId(?int $playingId): static;

    public function setCommunityState(int $communityState): static;

    public function setProfileState(int $profileState): static;

    public function setPersona(string $persona): static;

    public function setCommentPermission(int $commentPermission): static;

    public function setUrl(string $url): static;

    public function setAvatar(array $avatar): static;

    public function setLogoff(?int $logoff): static;

    public function setPersonaState(int $personaState): static;

    public function setName(?string $name): static;

    public function setClanId(?int $clanId): static;

    public function setCreatedAt(int $createdAt): static;

    public function setPersonaFlags(?int $personaFlags): static;

    public function setCountryCode(?string $countryCode): static;

    public function setStateCode(?int $stateCode): static;

    public function setOpenID(string $openId): static;

    public function setUsername(?string $username): static;
}
