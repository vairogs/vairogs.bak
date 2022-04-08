<?php declare(strict_types = 1);

namespace Vairogs\Tests\Utils\Helper;

use PHPUnit\Framework\TestCase;
use Vairogs\Utils\Helper\Email;

class EmailTest extends TestCase
{
    /**
     * @dataProvider dataProviderIsValid
     */
    public function testIsValid(string $email, bool $expected): void
    {
        $this->assertSame(expected: $expected, actual: Email::isValid(email: $email));
    }

    public function dataProviderIsValid(): array
    {
        return [
            ['vairogs@vairogs.com', true],
            ['vairogs', false],
            ['vairogs@vairogs', false],
            ['vairogs@vairogs.123', false],
            ['', false],
        ];
    }
}
