<?php declare(strict_types = 1);

namespace Vairogs\Addon\Auth\OpenID\Steam\Contracts;

use Vairogs\Auth\OpenID\Contracts\OpenIDUser;

interface User extends OpenIDUser
{
    public function getPlaying(): string;

    public function getPlayingId(): int;

    public function getCommunityState(): int;

    public function getProfileState(): int;

    public function getPersona(): string;

    public function getCommentPermission(): int;

    public function getUrl(): string;

    public function getAvatar(): array;

    public function getLogoff(): int;

    public function getPersonaState(): int;

    public function getName(): string;

    public function getClanId(): int;

    public function getCreatedAt(): int;

    public function getPersonaFlags(): int;

    public function getCountryCode(): string;

    public function getStateCode(): int;

    public function getUsername(): string;

    public function setPlaying(string $playing): static;

    public function setPlayingId(int $playingId): static;

    public function setCommunityState(int $communityState): static;

    public function setProfileState(int $profileState): static;

    public function setPersona(string $persona): static;

    public function setCommentPermission(int $commentPermission): static;

    public function setUrl(string $url): static;

    public function setAvatar(array $avatar): static;

    public function setLogoff(int $logoff): static;

    public function setPersonaState(int $personaState): static;

    public function setName(string $name): static;

    public function setClanId(int $clanId): static;

    public function setCreatedAt(int $createdAt): static;

    public function setPersonaFlags(int $personaFlags): static;

    public function setCountryCode(string $countryCode): static;

    public function setStateCode(int $stateCode): static;

    public function setOpenID(string $openId): static;

    public function setUsername(string $username): static;
}
