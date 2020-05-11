<?php declare(strict_types = 1);

namespace Vairogs\Addon\Auth\OpenID\Steam\Builder;

use Vairogs\Component\Auth\OpenID\Contracts\OpenIDUserBuilder;
use Vairogs\Component\Auth\OpenID\Contracts\OpenIDUser;
use Vairogs\Addon\Auth\OpenID\Steam\Model\SteamGiftsUser;
use Vairogs\Addon\Auth\OpenID\Steam\Model\SteamGiftsUserArrayFactory;

class SteamGiftsUserBuilder implements OpenIDUserBuilder
{
    protected string $cacheDir;

    public function getUser(array $response): OpenIDUser
    {
        $this->cacheDir = $response['cache_dir'];

        return $this->getSteamGiftsUser($response);
    }

    protected function getSteamGiftsUser(array $data): SteamGiftsUser
    {
        $input = $data['response']['players'][0];
        $input['username'] = $this->getUsername($input['steamid']);

        return SteamGiftsUserArrayFactory::create($input);
    }

    protected function getUsername(string $user): ?string
    {
        $dir = $this->cacheDir . DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR . $user . '.txt';
        exec('wget --no-verbose --spider --output-file=' . $dir . " -e robots=off -U='Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2837.0 Safari/537.36' https://www.steamgifts.com/go/user/" . $user);
        $file = file_get_contents($dir);
        preg_match_all('!https?://\S+!', $file, $matches);
        $expl = explode('/', $matches[0][0]);
        $username = null;
        if (trim(end($expl)) !== '') {
            $username = end($expl);
        }

        return $username;
    }
}
