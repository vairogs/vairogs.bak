<?php declare(strict_types = 1);

namespace Vairogs\Tests\Utils\Helper;

use PHPUnit\Framework\TestCase;
use Vairogs\Utils\Helper\Gravatar;

class GravatarTest extends TestCase
{
    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\GravatarDataProvider::dataProviderGetGravatarUrl
     */
    public function testGetGravatarUrl(string $email, bool $secure, int $size, string $icon, string $expected): void
    {
        $this->assertSame(expected: $expected, actual: Gravatar::getGravatarUrl($email, $secure, $size, $icon));
    }
}
