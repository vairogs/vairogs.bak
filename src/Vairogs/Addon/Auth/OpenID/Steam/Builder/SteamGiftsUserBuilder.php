<?php declare(strict_types = 1);

namespace Vairogs\Addon\Auth\OpenID\Steam\Builder;

use Vairogs\Addon\Auth\OpenID\Steam\Model\SteamGifts;
use Vairogs\Addon\Auth\OpenID\Steam\Model\User;
use Vairogs\Addon\Auth\OpenID\Steam\UserArrayFactory;
use Vairogs\Auth\OpenID\Builder\OpenIDUserBuilder;
use Vairogs\Auth\OpenID\Model\OpenIDUser;
use Vairogs\Functions\Constants\Definition;
use Vairogs\Functions\File;

use function dirname;
use function end;
use function exec;
use function explode;
use function file_get_contents;
use function is_file;
use function preg_match_all;
use function sys_get_temp_dir;
use function trim;

use const DIRECTORY_SEPARATOR;

class SteamGiftsUserBuilder implements OpenIDUserBuilder
{
    protected string $cacheDir;
    protected string $userClass = SteamGifts::class;

    public function getUser(array $response): OpenIDUser
    {
        $this->cacheDir = (string) ($response['cache_dir'] ?? sys_get_temp_dir());

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
        $input = (array) $data['response']['players'][0];
        $input['username'] = $this->getUsername(user: $input['steamid']);

        return (new UserArrayFactory())->create(user: new $this->userClass(), bag: $input);
    }

    private function getUsername(string $user): ?string
    {
        $path = $this->cacheDir . DIRECTORY_SEPARATOR . 'users' . DIRECTORY_SEPARATOR . $user . '.txt';

        /* @noinspection NotOptimalIfConditionsInspection */
        if ((new File())->mkdir(dir: dirname(path: $path)) && !is_file(filename: $path)) {
            exec(command: 'wget --no-verbose --spider --output-file=' . $path . " -e robots=off -U='" . Definition::UA . "' https://www.steamgifts.com/go/user/" . $user);
        }

        if (!is_file(filename: $path)) {
            return null;
        }

        $matches = [];
        preg_match_all(pattern: '#https?://\S+#', subject: (string) file_get_contents(filename: $path), matches: $matches);
        $expl = explode(separator: '/', string: (string) ($matches[0][0] ?? ''));
        $username = null;

        if ('' !== trim(string: end(array: $expl))) {
            $username = trim(string: end(array: $expl));
        }

        return $username;
    }
}
