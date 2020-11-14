<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\Helper;

use InvalidArgumentException;
use ReflectionException;
use function hash;
use function http_build_query;
use function strtolower;
use function trim;
use function urldecode;

class Gravatar
{
    public const ICON_404 = '404';
    public const ICON_MM = 'mm';
    public const ICON_IDENTICON = 'identicon';
    public const ICON_MONSTERID = 'monsterid';
    public const ICON_WAVATAR = 'wavatar';
    public const ICON_RETRO = 'retro';
    public const ICON_BLANK = 'blank';
    public const DEFAULT_EMAIL = 'vairogs@vairogs.com';
    public const HTTP_HOST = 'http://www.gravatar.com';
    public const HTTPS_HOST = 'https://secure.gravatar.com';

    /**
     * @param string $email
     * @param bool $isSecure
     * @param int $size
     * @param string $default
     *
     * @return string
     * @throws ReflectionException
     * @throws InvalidArgumentException
     */
    public static function getGravatarUrl(string $email, bool $isSecure = false, int $size = 32, string $default = self::ICON_IDENTICON): string
    {
        if (empty($email) || false === Email::isValid($email)) {
            $email = self::DEFAULT_EMAIL;
        }

        $host = self::HTTP_HOST;
        if (true === $isSecure) {
            $host = self::HTTPS_HOST;
        }

        if (Http::isAbsolute($default)) {
            $default = urldecode($default);
        } else {
            $default = self::getIcons()['ICON_' . $default] ?? self::ICON_IDENTICON;
        }

        $query = [
            's' => $size,
            'd' => $default,
        ];

        return $host . '/avatar/' . hash('md5', strtolower(trim($email))) . '/?' . http_build_query($query);
    }

    /**
     * @return array
     * @throws InvalidArgumentException
     * @throws ReflectionException
     */
    private static function getIcons(): array
    {
        return Iter::filterKeyStartsWith(Php::getClassConstants(__CLASS__), 'ICON_');
    }
}
