<?php declare(strict_types = 1);

namespace Vairogs\Functions;

use Symfony\Component\Intl\Countries;

use function str_replace;
use function strtoupper;

final class Cyrillic
{
    public const MAP_CYRILLIC = [
        'е', 'ё', 'ж', 'х', 'ц', 'ч', 'ш', 'щ', 'ю', 'я',
        'Е', 'Ё', 'Ж', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ю', 'Я',
        'а', 'б', 'в', 'г', 'д', 'з', 'и', 'й', 'к', 'л',
        'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'ъ',
        'ы', 'ь', 'э', 'А', 'Б', 'В', 'Г', 'Д', 'З', 'И',
        'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т',
        'У', 'Ф', 'Ъ', 'Ы', 'Ь', 'Э',
    ];
    public const MAP_LATIN = [
        'ye', 'ye', 'zh', 'kh', 'ts', 'ch', 'sh', 'shch', 'yu', 'ya',
        'Ye', 'Ye', 'Zh', 'Kh', 'Ts', 'Ch', 'Sh', 'Shch', 'Yu', 'Ya',
        'a', 'b', 'v', 'g', 'd', 'z', 'i', 'y', 'k', 'l',
        'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'ʺ',
        'y', '–', 'e', 'A', 'B', 'V', 'G', 'D', 'Z', 'I',
        'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T',
        'U', 'F', 'ʺ', 'Y', '–', 'E',
    ];

    public function cyrillic2latin(string $text, array $search = self::MAP_CYRILLIC, array $replace = self::MAP_LATIN): string
    {
        return str_replace(search: $search, replace: $replace, subject: $text);
    }

    public function latin2cyrillic(string $text, array $search = self::MAP_LATIN, array $replace = self::MAP_CYRILLIC): string
    {
        return str_replace(search: $search, replace: $replace, subject: $text);
    }

    public function getCountryName(string $country, string $locale = 'en'): string
    {
        return Countries::getName(country: strtoupper(string: $country), displayLocale: $locale);
    }
}
