<?php declare(strict_types = 1);

namespace Vairogs\Addon\Auth\OpenID\Steam;

use Vairogs\Addon\Auth\OpenID\Steam\Model\User;

class UserArrayFactory
{
    private const STEAMID = 'steamid';

    public function create(User $user, array $bag): User
    {
        $avatar = [
            'large' => (string) ($bag['avatarfull'] ?? ''),
            'medium' => (string) ($bag['avatarmedium'] ?? ''),
            'small' => (string) ($bag['avatar'] ?? ''),
            'hash' => (string) ($bag['avatarhash'] ?? ''),
        ];

        return $user
            ->setOpenID(openId: (string) ($bag[self::STEAMID]))
            ->setAvatar(avatar: $avatar)
            ->setClanId(clanId: (int) ($bag['primaryclanid'] ?? 0))
            ->setCommentPermission(commentPermission: (int) ($bag['commentpermission'] ?? 0))
            ->setCommunityState(communityState: (int) ($bag['communityvisibilitystate'] ?? 0))
            ->setCountryCode(countryCode: (string) ($bag['loccountrycode'] ?? 'UNKNOWN'))
            ->setCreatedAt(createdAt: (int) ($bag['timecreated'] ?? 0))
            ->setLogoff(logoff: (int) ($bag['lastlogoff'] ?? 0))
            ->setName(name: (string) ($bag['realname'] ?? $bag['personaname'] ?? $bag[self::STEAMID]))
            ->setPersona(persona: (string) ($bag['personaname'] ?? $bag[self::STEAMID]))
            ->setPersonaFlags(personaFlags: (int) ($bag['personastateflags'] ?? 0))
            ->setPersonaState(personaState: (int) ($bag['personastate'] ?? 0))
            ->setPlaying(playing: (string) ($bag['gameextrainfo'] ?? ''))
            ->setPlayingId(playingId: (int) ($bag['gameid'] ?? 0))
            ->setProfileState(profileState: (int) ($bag['profilestate'] ?? 0))
            ->setStateCode(stateCode: (int) ($bag['locstatecode'] ?? 0))
            ->setUrl(url: (string) ($bag['profileurl'] ?? ''))
            ->setUsername(username: (string) ($bag['username'] ?? $bag[self::STEAMID]));
    }
}
