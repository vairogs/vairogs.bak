<?php declare(strict_types = 1);

namespace Vairogs\Addon\Auth\OpenID\Steam\Contracts;

use Vairogs\Component\Auth\OpenID\Contracts\OpenIDUser;

interface User extends OpenIDUser
{
    public function getPlaying(): ?string;

    public function setPlaying(?string $playing): static;

    public function getPlayingId(): ?int;

    public function setPlayingId(?int $playingId): static;

    public function getCommunityState(): int;

    public function setCommunityState(int $communityState): static;

    public function getProfileState(): int;

    public function setProfileState(int $profileState): static;

    public function getPersona(): string;

    public function setPersona(string $persona): static;

    public function getCommentPermission(): int;

    public function setCommentPermission(int $commentPermission): static;

    public function getUrl(): string;

    public function setUrl(string $url): static;

    public function getAvatar(): array;

    public function setAvatar(array $avatar): static;

    public function getLogoff(): ?int;

    public function setLogoff(?int $logoff): static;

    public function getPersonaState(): int;

    public function setPersonaState(int $personaState): static;

    public function getName(): ?string;

    public function setName(?string $name): static;

    public function getClanId(): ?int;

    public function setClanId(?int $clanId): static;

    public function getCreatedAt(): int;

    public function setCreatedAt(int $createdAt): static;

    public function getPersonaFlags(): ?int;

    public function setPersonaFlags(?int $personaFlags): static;

    public function getCountryCode(): ?string;

    public function setCountryCode(?string $countryCode): static;

    public function getStateCode(): ?int;

    public function setStateCode(?int $stateCode): static;

    public function getEmail(): ?string;

    public function getOpenID(): string;

    public function returnsEmail(): bool;

    public function setOpenID(string $openId): static;

    public function getUsername(): ?string;

    public function setUsername(?string $username): static;
}
