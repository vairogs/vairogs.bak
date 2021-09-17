<?php declare(strict_types = 1);

namespace Vairogs\Addon\Auth\OpenID\Steam\Builder;

use Vairogs\Addon\Auth\OpenID\Steam\Contracts\User;
use Vairogs\Addon\Auth\OpenID\Steam\Model\SteamGifts;
use Vairogs\Addon\Auth\OpenID\Steam\UserArrayFactory;
use Vairogs\Component\Auth\OpenID\Contracts\OpenIDUser;
use Vairogs\Component\Auth\OpenID\Contracts\OpenIDUserBuilder;
use Vairogs\Component\Utils\Helper\File;
use function end;
use function exec;
use function explode;
use function file_get_contents;
use function is_file;
use function preg_match_all;
use function trim;

class SteamGiftsUserBuilder implements OpenIDUserBuilder
{
    protected string $cacheDir;
    protected string $userClass = SteamGifts::class;

    public function getUser(array $response): OpenIDUser
    {
        $this->cacheDir = $response['cache_dir'];

        return $this->getSteamGiftsUser(data: $response);
    }

    public function setUserClass(string $class): static
    {
        $this->userClass = $class;

        return $this;
    }

    public function getUserClass(): string
    {
        return $this->userClass;
    }

    private function getSteamGiftsUser(array $data): User
    {
        $input = $data['response']['players'][0];
        $input['username'] = $this->getUsername(user: $input['steamid']);

        return UserArrayFactory::create(user: new $this->userClass(), bag: $input);
    }

    private function getUsername(string $user): ?string
    {
        $path = $this->cacheDir . DIRECTORY_SEPARATOR . 'users' . DIRECTORY_SEPARATOR . $user . '.txt';

        if (File::mkdir(path: $path) && !is_file(filename: $path)) {
            exec(command: 'wget --no-verbose --spider --output-file=' . $path . " -e robots=off -U='Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/93.0.4577.63 Safari/537.36' https://www.steamgifts.com/go/user/" . $user);
        }

        if (!is_file(filename: $path)) {
            return null;
        }

        $file = file_get_contents(filename: $path);
        preg_match_all(pattern: '#https?://\S+#', subject: $file, matches: $matches);
        $expl = explode(separator: '/', string: $matches[0][0]);
        $username = null;

        if ('' !== trim(string: end(array: $expl))) {
            $username = trim(string: end(array: $expl));
        }

        return $username;
    }
}
