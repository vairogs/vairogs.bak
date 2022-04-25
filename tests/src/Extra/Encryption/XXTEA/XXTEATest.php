<?php declare(strict_types = 1);

namespace Vairogs\Tests\Extra\Encryption\XXTEA;

use PHPUnit\Framework\TestCase;
use Vairogs\Extra\Encryption\XXTEA\XXTEA;

class XXTEATest extends TestCase
{
    /**
     * @dataProvider \Vairogs\Assets\Extra\Encryption\XXTEA\XXTEADataProvider::dataProvider
     */
    public function testDecrypt(string $string, string $key): void
    {
        $this->assertEquals(expected: $string, actual: XXTEA::decrypt(string: XXTEA::encrypt(string: $string, key: $key), key: $key));
    }
}