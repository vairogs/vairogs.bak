<?php declare(strict_types = 1);

namespace Vairogs\Tests\Utils\Helper;

use PHPUnit\Framework\TestCase;
use Vairogs\Utils\Helper\Email;

class EmailTest extends TestCase
{
    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\EmailDataProvider::dataProviderIsValid
     */
    public function testIsValid(string $email, bool $expected): void
    {
        $this->assertSame(expected: $expected, actual: Email::isValid(email: $email));
    }
}
