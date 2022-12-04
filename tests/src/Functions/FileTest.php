<?php declare(strict_types = 1);

namespace Vairogs\Tests\Source\Functions;

use UnexpectedValueException;
use Vairogs\Functions\File;
use Vairogs\Functions\Identification;
use Vairogs\Tests\Assets\VairogsTestCase;

use const DIRECTORY_SEPARATOR;

class FileTest extends VairogsTestCase
{
    /**
     * @dataProvider \Vairogs\Tests\Assets\Functions\FileDataProvider::dataProviderHumanFileSize
     */
    public function testHumanFileSize(int $bytes, int $decimals, string $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new File())->humanFileSize(bytes: $bytes, decimals: $decimals));
    }

    public function testMkdirRmdir(): void
    {
        $this->assertTrue(condition: (new File())->mkdir(dir: $this->directory));
        $this->assertTrue(condition: (new File())->rmdir(directory: $this->directory));

        $this->expectException(exception: UnexpectedValueException::class);
        (new File())->mkdir(dir: DIRECTORY_SEPARATOR . (new Identification())->getUniqueId(length: 10));
    }

    public function testFileExistsCurrentDir(): void
    {
        $this->assertTrue(condition: (new File())->fileExistsCwd(filename: 'README.md'));
    }
}
