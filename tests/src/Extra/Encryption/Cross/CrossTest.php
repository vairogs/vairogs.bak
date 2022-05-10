<?php declare(strict_types = 1);

namespace Vairogs\Tests\Extra\Encryption\Cross;

use Vairogs\Assets\VairogsTestCase;
use Vairogs\Extra\Encryption\Cross\Cross;

class CrossTest extends VairogsTestCase
{
    /**
     * @dataProvider \Vairogs\Assets\Extra\Encryption\XXTEA\XXTEADataProvider::dataProvider
     */
    public function testDecrypt(string $string, string $key): void
    {
        $this->assertEquals(expected: $string, actual: (new Cross())->decrypt(string: (new Cross())->encrypt(string: $string, key: $key), key: $key));
    }
}
