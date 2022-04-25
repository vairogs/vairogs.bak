<?php declare(strict_types = 1);

namespace Vairogs\Tests\Utils\Helper;

use PHPUnit\Framework\TestCase;
use Vairogs\Utils\Helper\Composer;

class ComposerTest extends TestCase
{
    public function testGetEnv(): void
    {
        $this->assertSame(expected: '-1', actual: Composer::getEnv(varname: 'SHELL_VERBOSITY'));
        $this->assertSame(expected: '1', actual: Composer::getEnv(varname: 'PHP_CS_FIXER_IGNORE_ENV', localOnly: false));
        $this->assertSame(expected: 'TEST', actual: Composer::getEnv(varname: 'TEST'));
    }

    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\ComposerDataProvider::dataProviderIsInstalled
     */
    public function testIsInstalled(string $package, bool $installed, bool $incDevReq): void
    {
        $this->assertSame(expected: $installed, actual: Composer::isInstalled(packages: [$package], incDevReq: $incDevReq));
    }
}
