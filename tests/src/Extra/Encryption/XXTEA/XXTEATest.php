<?php declare(strict_types = 1);

namespace Vairogs\Tests\Extra\Encryption\XXTEA;

use PHPUnit\Framework\TestCase;
use Vairogs\Extra\Encryption\XXTEA\XXTEA;

class XXTEATest extends TestCase
{
    /**
     * @dataProvider \Vairogs\Assets\Extra\Encryption\XXTEA\XXTEADataProvider::dataProvider
     */
    public function testEncrypt(string $string, string $key, string $expected): void
    {
        $this->assertEquals(expected: $expected, actual: base64_encode(string: XXTEA::encrypt(string: $string, key: $key)));
    }

    /**
     * @dataProvider \Vairogs\Assets\Extra\Encryption\XXTEA\XXTEADataProvider::dataProvider
     */
    public function testDecrypt(string $string, string $key): void
    {
        $this->assertEquals(expected: $string, actual: XXTEA::decrypt(string: XXTEA::encrypt(string: $string, key: $key), key: $key));
    }
}
