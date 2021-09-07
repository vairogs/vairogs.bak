<?php declare(strict_types = 1);

namespace Vairogs\Addon\Auth\OpenID\Steam\Contracts;

use Vairogs\Component\Auth\OpenID\Contracts\OpenIDUser;

interface User extends OpenIDUser
{
    public function getPlaying(): ?string;

    public function setPlaying(?string $playing): User;

    public function getPlayingId(): ?int;

    public function setPlayingId(?int $playingId): User;

    public function getCommunityState(): int;

    public function setCommunityState(int $communityState): User;

    public function getProfileState(): int;

    public function setProfileState(int $profileState): User;

    public function getPersona(): string;

    public function setPersona(string $persona): User;

    public function getCommentPermission(): int;

    public function setCommentPermission(int $commentPermission): User;

    public function getUrl(): string;

    public function setUrl(string $url): User;

    public function getAvatar(): array;

    public function setAvatar(array $avatar): User;

    public function getLogoff(): ?int;

    public function setLogoff(?int $logoff): User;

    public function getPersonaState(): int;

    public function setPersonaState(int $personaState): User;

    public function getName(): ?string;

    public function setName(?string $name): User;

    public function getClanId(): ?int;

    public function setClanId(?int $clanId): User;

    public function getCreatedAt(): int;

    public function setCreatedAt(int $createdAt): User;

    public function getPersonaFlags(): ?int;

    public function setPersonaFlags(?int $personaFlags): User;

    public function getCountryCode(): ?string;

    public function setCountryCode(?string $countryCode): User;

    public function getStateCode(): ?int;

    public function setStateCode(?int $stateCode): User;

    public function getEmail(): ?string;

    public function getOpenID(): string;

    public function returnsEmail(): bool;

    public function setOpenID(string $openId): User;

    public function getUsername(): ?string;

    public function setUsername(?string $username): User;
}
