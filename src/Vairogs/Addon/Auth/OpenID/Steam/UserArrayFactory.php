<?php declare(strict_types = 1);

namespace Vairogs\Addon\Auth\OpenID\Steam;

use Vairogs\Addon\Auth\OpenID\Steam\Contracts\User;
use Vairogs\Extra\Constants\Status;

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
            ->setCommunityState(communityState: $bag['communityvisibilitystate'] ?? Status::ZERO)
            ->setProfileState(profileState: $bag['profilestate'] ?? Status::ZERO)
            ->setPersona(persona: $bag['personaname'] ?? $bag[self::STEAMID])
            ->setCommentPermission(commentPermission: $bag['commentpermission'] ?? Status::ZERO)
            ->setUrl(url: $bag['profileurl'] ?? '')
            ->setLogoff(logoff: $bag['lastlogoff'] ?? Status::ZERO)
            ->setPersonaState(personaState: $bag['personastate'] ?? Status::ZERO)
            ->setName(name: $bag['realname'] ?? $bag['personaname'] ?? $bag[self::STEAMID])
            ->setClanId(clanId: (int) ($bag['primaryclanid'] ?? Status::ZERO))
            ->setCreatedAt(createdAt: $bag['timecreated'] ?? Status::ZERO)
            ->setPersonaFlags(personaFlags: $bag['personastateflags'] ?? Status::ZERO)
            ->setCountryCode(countryCode: $bag['loccountrycode'] ?? 'UNKNOWN')
            ->setStateCode(stateCode: (int) ($bag['locstatecode'] ?? Status::ZERO))
            ->setPlaying(playing: $bag['gameextrainfo'] ?? '')
            ->setPlayingId(playingId: (int) ($bag['gameid'] ?? Status::ZERO))
            ->setAvatar(avatar: $avatar)
            ->setUsername(username: $bag['username'] ?? null);
    }
}
