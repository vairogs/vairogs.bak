<?php declare(strict_types = 1);

namespace Vairogs\Utils\Helper;

use InvalidArgumentException;
use RuntimeException;
use Vairogs\Twig\Attribute\TwigFilter;
use Vairogs\Twig\Attribute\TwigFunction;
use function hash;
use function http_build_query;
use function strtolower;
use function strtoupper;
use function trim;
use function urldecode;

final class Gravatar
{
    final public const ICON_404 = '404';
    final public const ICON_MM = 'mm';
    final public const ICON_IDENTICON = 'identicon';
    final public const ICON_MONSTERID = 'monsterid';
    final public const ICON_WAVATAR = 'wavatar';
    final public const ICON_RETRO = 'retro';
    final public const ICON_BLANK = 'blank';
    final public const DEFAULT_EMAIL = 'vairogs@vairogs.com';
    final public const HTTP_HOST = 'http://www.gravatar.com';
    final public const HTTPS_HOST = 'https://secure.gravatar.com';

    /**
     * @throws RuntimeException
     * @throws InvalidArgumentException
     */
    #[TwigFunction]
    #[TwigFilter]
    public function getGravatarUrl(string $email, bool $isSecure = false, int $size = 32, string $default = self::ICON_IDENTICON): string
    {
        if (!(new Validate())->validateEmail(email: $email)) {
            $email = self::DEFAULT_EMAIL;
        }

        $host = match ($isSecure) {
            true => self::HTTPS_HOST,
            false => self::HTTP_HOST
        };

        $default = match (true) {
            (new Uri())->isAbsolute(path: $default) => urldecode(string: $default),
            default => $this->getIcons()['ICON_' . strtoupper($default)] ?? self::ICON_IDENTICON
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
