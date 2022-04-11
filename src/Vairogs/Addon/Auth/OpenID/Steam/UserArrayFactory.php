<?php declare(strict_types = 1);

namespace Vairogs\Addon\Auth\OpenID\Steam;

use Vairogs\Addon\Auth\OpenID\Steam\Contracts\User;

class UserArrayFactory
{
    private const STEAMID = 'steamid';

    public static function create(User $user, array $bag): User
    {
        $avatar = [
            'small' => $bag['avatar'] ?? '',
            'medium' => $bag['avatarmedium'] ?? '',
            'largs' => $bag['avatarfull'] ?? '',
            'hash' => $bag['avatarhash'],
        ];

        return $user->setOpenID(openId: $bag[self::STEAMID])
            ->setCommunityState(communityState: $bag['communityvisibilitystate'] ?? 0)
            ->setProfileState(profileState: $bag['profilestate'] ?? 0)
            ->setPersona(persona: $bag['personaname'] ?? $bag[self::STEAMID])
            ->setCommentPermission(commentPermission: $bag['commentpermission'] ?? 0)
            ->setUrl(url: $bag['profileurl'] ?? '')
            ->setLogoff(logoff: $bag['lastlogoff'] ?? 0)
            ->setPersonaState(personaState: $bag['personastate'] ?? 0)
            ->setName(name: $bag['realname'] ?? $bag['personaname'] ?? $bag[self::STEAMID])
            ->setClanId(clanId: (int) ($bag['primaryclanid'] ?? 0))
            ->setCreatedAt(createdAt: $bag['timecreated'] ?? 0)
            ->setPersonaFlags(personaFlags: $bag['personastateflags'] ?? 0)
            ->setCountryCode(countryCode: $bag['loccountrycode'] ?? 'UNKNOWN')
            ->setStateCode(stateCode: (int) ($bag['locstatecode'] ?? 0))
            ->setPlaying(playing: $bag['gameextrainfo'] ?? '')
            ->setPlayingId(playingId: (int) ($bag['gameid'] ?? 0))
            ->setAvatar(avatar: $avatar)
            ->setUsername(username: $bag['username'] ?? null);
    }
}
