<?php declare(strict_types = 1);

namespace Vairogs\Addon\Auth\OpenID\Steam;

use Vairogs\Addon\Auth\OpenID\Steam\Contracts\User;

class UserArrayFactory
{
    private const STEAMID = 'steamid';

    public function create(User $user, array $bag): User
    {
        $avatar = [
            'small' => (string) ($bag['avatar'] ?? ''),
            'medium' => (string) ($bag['avatarmedium'] ?? ''),
            'largs' => (string) ($bag['avatarfull'] ?? ''),
            'hash' => (string) ($bag['avatarhash']),
        ];

        return $user
            ->setOpenID(openId: (string) ($bag[self::STEAMID]))
            ->setCommunityState(communityState: (int) ($bag['communityvisibilitystate'] ?? 0))
            ->setProfileState(profileState: (int) ($bag['profilestate'] ?? 0))
            ->setPersona(persona: (string) ($bag['personaname'] ?? $bag[self::STEAMID]))
            ->setCommentPermission(commentPermission: (int) ($bag['commentpermission'] ?? 0))
            ->setUrl(url: (string) ($bag['profileurl'] ?? ''))
            ->setLogoff(logoff: (int) ($bag['lastlogoff'] ?? 0))
            ->setPersonaState(personaState: (int) ($bag['personastate'] ?? 0))
            ->setName(name: (string) ($bag['realname'] ?? $bag['personaname'] ?? $bag[self::STEAMID]))
            ->setClanId(clanId: (int) ($bag['primaryclanid'] ?? 0))
            ->setCreatedAt(createdAt: (int) ($bag['timecreated'] ?? 0))
            ->setPersonaFlags(personaFlags: (int) ($bag['personastateflags'] ?? 0))
            ->setCountryCode(countryCode: (string) ($bag['loccountrycode'] ?? 'UNKNOWN'))
            ->setStateCode(stateCode: (int) ($bag['locstatecode'] ?? 0))
            ->setPlaying(playing: (string) ($bag['gameextrainfo'] ?? ''))
            ->setPlayingId(playingId: (int) ($bag['gameid'] ?? 0))
            ->setAvatar(avatar: $avatar)
            ->setUsername(username: (string) ($bag['username'] ?? $bag[self::STEAMID]));
    }
}
