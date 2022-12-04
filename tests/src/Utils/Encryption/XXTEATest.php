<?php declare(strict_types = 1);

namespace Vairogs\Tests\Source\Utils\Encryption;

use Vairogs\Tests\Assets\VairogsTestCase;
use Vairogs\Utils\Encryption\XXTEA;

class XXTEATest extends VairogsTestCase
{
    /**
     * @dataProvider \Vairogs\Tests\Assets\Utils\Encryption\XXTEADataProvider::dataProvider
     */
    public function testDecrypt(string $string, string $key): void
    {
        $this->assertEquals(expected: $string, actual: (new XXTEA())->decrypt(string: (new XXTEA())->encrypt(string: $string, key: $key), key: $key));
    }
}
