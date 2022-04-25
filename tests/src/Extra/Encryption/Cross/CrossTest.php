<?php declare(strict_types = 1);

namespace Vairogs\Tests\Extra\Encryption\Cross;

use PHPUnit\Framework\TestCase;
use Vairogs\Extra\Encryption\Cross\Cross;

class CrossTest extends TestCase
{
    /**
     * @dataProvider \Vairogs\Assets\Extra\Encryption\XXTEA\XXTEADataProvider::dataProvider
     */
    public function testDecrypt(string $string, string $key): void
    {
        $this->assertEquals(expected: $string, actual: Cross::decrypt(string: Cross::encrypt(string: $string, key: $key), key: $key));
    }
}
