<?php declare(strict_types = 1);

namespace Vairogs\Tests\Utils\Helper;

use UnexpectedValueException;
use Vairogs\Assets\VairogsTestCase;
use Vairogs\Core\Vairogs;
use Vairogs\Utils\Helper\File;
use Vairogs\Utils\Helper\Identification;
use function sys_get_temp_dir;
use const DIRECTORY_SEPARATOR;

class FileTest extends VairogsTestCase
{
    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\FileDataProvider::dataProviderHumanFileSize
     */
    public function testHumanFileSize(int $bytes, int $decimals, string $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new File())->humanFileSize(bytes: $bytes, decimals: $decimals));
    }

    public function testMkdirRmdir(): void
    {
        $this->assertTrue(condition: (new File())->mkdir(dir: $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . Vairogs::VAIROGS));
        $this->assertTrue(condition: (new File())->rmdir(directory: $path));

        $this->expectException(exception: UnexpectedValueException::class);
        (new File())->mkdir(dir: DIRECTORY_SEPARATOR . (new Identification())->getUniqueId(length: 10));
    }

    public function testFileExistsCurrentDir(): void
    {
        $this->assertTrue(condition: (new File())->fileExistsCurrentDir(filename: 'README.md'));
    }
}
