<?php declare(strict_types = 1);

namespace Vairogs\Tests\Source\Extra\Encryption\Cross;

use Vairogs\Extra\Encryption\Cross\Cross;
use Vairogs\Tests\Assets\VairogsTestCase;

class CrossTest extends VairogsTestCase
{
    /**
     * @dataProvider \Vairogs\Tests\Assets\Extra\Encryption\XXTEA\XXTEADataProvider::dataProvider
     */
    public function testDecrypt(string $string, string $key): void
    {
        $this->assertEquals(expected: $string, actual: (new Cross())->decrypt(string: (new Cross())->encrypt(string: $string, key: $key), key: $key));
    }
}
