<?php declare(strict_types = 1);

namespace Vairogs\Addon\Auth\OpenID\Steam\Contracts;

interface User
{
    /**
     * @return string|null
     */
    public function getPlaying(): ?string;

    /**
     * @param string|null $playing
     *
     * @return User
     */
    public function setPlaying(?string $playing): User;

    /**
     * @return int|null
     */
    public function getPlayingId(): ?int;

    /**
     * @param int|null $playingId
     *
     * @return User
     */
    public function setPlayingId(?int $playingId): User;

    /**
     * @return int
     */
    public function getCommunityState(): int;

    /**
     * @param int $communityState
     *
     * @return User
     */
    public function setCommunityState(int $communityState): User;

    /**
     * @return int
     */
    public function getProfileState(): int;

    /**
     * @param int $profileState
     *
     * @return User
     */
    public function setProfileState(int $profileState): User;

    /**
     * @return string
     */
    public function getPersona(): string;

    /**
     * @param string $persona
     *
     * @return User
     */
    public function setPersona(string $persona): User;

    /**
     * @return int
     */
    public function getCommentPermission(): int;

    /**
     * @param int $commentPermission
     *
     * @return User
     */
    public function setCommentPermission(int $commentPermission): User;

    /**
     * @return string
     */
    public function getUrl(): string;

    /**
     * @param string $url
     *
     * @return User
     */
    public function setUrl(string $url): User;

    /**
     * @return array
     */
    public function getAvatar(): array;

    /**
     * @param array $avatar
     *
     * @return User
     */
    public function setAvatar(array $avatar): User;

    /**
     * @return int|null
     */
    public function getLogoff(): ?int;

    /**
     * @param int|null $logoff
     *
     * @return User
     */
    public function setLogoff(?int $logoff): User;

    /**
     * @return int
     */
    public function getPersonaState(): int;

    /**
     * @param int $personaState
     *
     * @return User
     */
    public function setPersonaState(int $personaState): User;

    /**
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * @param string|null $name
     *
     * @return User
     */
    public function setName(?string $name): User;

    /**
     * @return int|null
     */
    public function getClanId(): ?int;

    /**
     * @param int|null $clanId
     *
     * @return User
     */
    public function setClanId(?int $clanId): User;

    /**
     * @return int
     */
    public function getCreatedAt(): int;

    /**
     * @param int $createdAt
     *
     * @return User
     */
    public function setCreatedAt(int $createdAt): User;

    /**
     * @return int
     */
    public function getPersonaFlags(): int;

    /**
     * @param int $personaFlags
     *
     * @return User
     */
    public function setPersonaFlags(int $personaFlags): User;

    /**
     * @return string|null
     */
    public function getCountryCode(): ?string;

    /**
     * @param string|null $countryCode
     *
     * @return User
     */
    public function setCountryCode(?string $countryCode): User;

    /**
     * @return int|null
     */
    public function getStateCode(): ?int;

    /**
     * @param int|null $stateCode
     *
     * @return User
     */
    public function setStateCode(?int $stateCode): User;

    /**
     * @return string|null
     */
    public function getEmail(): ?string;

    /**
     * @return string
     */
    public function getOpenID(): string;

    /**
     * @param string $openId
     *
     * @return User
     */
    public function setOpenID(string $openId): User;

    /**
     * @return bool
     */
    public function returnsEmail(): bool;

    /**
     * @return string|null
     */
    public function getUsername(): ?string;

    /**
     * @param string|null $username
     * @return User
     */
    public function setUsername(?string $username): User;
}
