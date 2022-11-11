<?php declare(strict_types = 1);

namespace Vairogs\Utils\Helper;

use InvalidArgumentException;
use RuntimeException;
use Vairogs\Core\Attribute\CoreFilter;
use Vairogs\Core\Attribute\CoreFunction;

use function hash;
use function http_build_query;
use function strtolower;
use function strtoupper;
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
    #[CoreFunction]
    #[CoreFilter]
    public function getGravatarUrl(string $email, bool $isSecure = false, int $size = 32, string $default = self::ICON_IDENTICON): string
    {
        if (!(new Validate())->validateEmail(email: $email)) {
            $email = self::DEFAULT_EMAIL;
        }

        $host = match ($isSecure) {
            true => self::HTTPS_HOST,
            false => self::HTTP_HOST,
        };

        $default = match (true) {
            (new Uri())->isAbsolute(path: $default) => urldecode(string: $default),
            default => $this->getIcons()['ICON_' . strtoupper($default)] ?? self::ICON_IDENTICON,
        };

        return $host . '/avatar/' . hash(algo: 'md5', data: strtolower(string: trim(string: $email))) . '/?' . http_build_query(data: ['s' => $size, 'd' => $default]);
    }

    /**
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    private function getIcons(): array
    {
        return (new Iteration())->filterKeyStartsWith(input: (new Php())->getClassConstants(class: self::class), startsWith: 'ICON_');
    }
}
