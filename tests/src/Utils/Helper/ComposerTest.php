<?php declare(strict_types = 1);

namespace Vairogs\Tests\Utils\Helper;

use Vairogs\Assets\VairogsTestCase;
use Vairogs\Utils\Helper\Composer;

class ComposerTest extends VairogsTestCase
{
    public function testGetEnv(): void
    {
        $this->assertEquals(expected: 'test', actual: (new Composer())->getenv(varname: 'ENVIRONMENT'));
        $this->assertEquals(expected: '1', actual: (new Composer())->getenv(varname: 'PHP_CS_FIXER_IGNORE_ENV', localOnly: false));
        $this->assertEquals(expected: 'TEST', actual: (new Composer())->getenv(varname: 'TEST'));
    }

    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\ComposerDataProvider::dataProviderIsInstalled
     */
    public function testIsInstalled(string $package, bool $installed, bool $incDevReq): void
    {
        $this->assertEquals(expected: $installed, actual: (new Composer())->isInstalled(packages: [$package], incDevReq: $incDevReq));
    }

    public function testExists(): void
    {
        $this->assertTrue(condition: (new Composer())->exists(class: Composer::class));
        $this->assertFalse(condition: (new Composer())->exists(class: 'Test'));
    }
}
