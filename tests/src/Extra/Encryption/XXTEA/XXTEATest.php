<?php declare(strict_types = 1);

namespace Vairogs\Tests\Extra\Encryption\XXTEA;

use Vairogs\Assets\VairogsTestCase;
use Vairogs\Extra\Encryption\XXTEA\XXTEA;

class XXTEATest extends VairogsTestCase
{
    /**
     * @dataProvider \Vairogs\Assets\Extra\Encryption\XXTEA\XXTEADataProvider::dataProvider
     */
    public function testDecrypt(string $string, string $key): void
    {
        $this->assertEquals(expected: $string, actual: (new XXTEA())->decrypt(string: (new XXTEA())->encrypt(string: $string, key: $key), key: $key));
    }
}
