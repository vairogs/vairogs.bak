<?php declare(strict_types = 1);

namespace Vairogs\Utils\Helper;

use InvalidArgumentException;
use RuntimeException;
use Vairogs\Utils\Twig\Attribute;
use function hash;
use function http_build_query;
use function strtolower;
use function trim;
use function urldecode;

final class Gravatar
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
     * @throws RuntimeException
     * @throws InvalidArgumentException
     */
    #[Attribute\TwigFilter]
    public static function getGravatarUrl(string $email, bool $isSecure = false, int $size = 32, string $default = self::ICON_IDENTICON): string
    {
        if (empty($email) || !Email::isValid(email: $email)) {
            $email = self::DEFAULT_EMAIL;
        }

        $host = self::HTTP_HOST;
        if ($isSecure) {
            $host = self::HTTPS_HOST;
        }

        if (Uri::isAbsolute(path: $default)) {
            $default = urldecode(string: $default);
        } else {
            $default = self::getIcons()['ICON_' . $default] ?? self::ICON_IDENTICON;
        }

        $query = [
            's' => $size,
            'd' => $default,
        ];

        return $host . '/avatar/' . hash(algo: 'md5', data: strtolower(string: trim(string: $email))) . '/?' . http_build_query(data: $query);
    }

    /**
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    private static function getIcons(): array
    {
        return Iteration::filterKeyStartsWith(input: Php::getClassConstants(class: self::class), startsWith: 'ICON_');
    }
}
