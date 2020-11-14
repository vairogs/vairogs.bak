<?php declare(strict_types = 1);

namespace Vairogs\Addon\Auth\OpenID\Steam\Builder;

use RuntimeException;
use Vairogs\Addon\Auth\OpenID\Steam\Contracts\User;
use Vairogs\Component\Auth\OpenID\Contracts\OpenIDUserBuilder;
use Vairogs\Component\Auth\OpenID\Contracts\OpenIDUser;
use Vairogs\Addon\Auth\OpenID\Steam\Model\SteamGifts;
use Vairogs\Addon\Auth\OpenID\Steam\UserArrayFactory;
use function dirname;
use function end;
use function exec;
use function explode;
use function file_get_contents;
use function is_dir;
use function mkdir;
use function preg_match_all;
use function sprintf;
use function trim;

class SteamGiftsUserBuilder implements OpenIDUserBuilder
{
    protected string $cacheDir;

    protected string $userClass = SteamGifts::class;

    /**
     * @param string $userClass
     * @return SteamGiftsUserBuilder
     */
    public function setUserClass(string $userClass): SteamGiftsUserBuilder
    {
        $this->userClass = $userClass;

        return $this;
    }

    /**
     * @param array $response
     *
     * @return OpenIDUser
     */
    public function getUser(array $response): OpenIDUser
    {
        $this->cacheDir = $response['cache_dir'];

        return $this->getSteamGiftsUser($response);
    }

    /**
     * @param array $data
     *
     * @return SteamGifts
     */
    protected function getSteamGiftsUser(array $data): User
    {
        $input = $data['response']['players'][0];
        $input['username'] = $this->getUsername($input['steamid']);

        return UserArrayFactory::create(new $this->userClass(), $input);
    }

    /**
     * @param string $user
     *
     * @return string|null
     */
    protected function getUsername(string $user): ?string
    {
        $path = $this->cacheDir . DIRECTORY_SEPARATOR . 'users' . DIRECTORY_SEPARATOR . $user . '.txt';
        $dir = dirname($path);
        if (!is_dir($dir) && !mkdir($dir, 0777, true) && !is_dir($dir)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $dir));
        }
        if (!is_file($path)) {
            exec('wget --no-verbose --spider --output-file=' . $path . " -e robots=off -U='Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2837.0 Safari/537.36' https://www.steamgifts.com/go/user/" . $user);
        }
        $file = file_get_contents($path);
        preg_match_all('!https?://\S+!', $file, $matches);
        $expl = explode('/', $matches[0][0]);
        $username = null;
        if (trim(end($expl)) !== '') {
            $username = end($expl);
        }

        return $username;
    }
}
