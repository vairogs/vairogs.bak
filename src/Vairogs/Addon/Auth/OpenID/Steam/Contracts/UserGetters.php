<?php declare(strict_types = 1);

namespace Vairogs\Addon\Auth\OpenID\Steam\Contracts;

interface UserGetters
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
}
