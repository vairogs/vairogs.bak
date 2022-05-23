<?php declare(strict_types = 1);

namespace Vairogs\Tests\Utils\Helper;

use Vairogs\Tests\Assets\VairogsTestCase;
use Vairogs\Utils\Helper\Gravatar;

class GravatarTest extends VairogsTestCase
{
    /**
     * @dataProvider \Vairogs\Tests\Assets\Utils\Helper\GravatarDataProvider::dataProviderGetGravatarUrl
     */
    public function testGetGravatarUrl(string $email, bool $secure, int $size, string $icon, string $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Gravatar())->getGravatarUrl(email: $email, isSecure: $secure, size: $size, default: $icon));
    }
}
