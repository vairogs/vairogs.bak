<?php declare(strict_types = 1);

namespace Vairogs\Functions\Tests;

use Vairogs\Core\Tests\VairogsTestCase;
use Vairogs\Functions\Composer;

class ComposerTest extends VairogsTestCase
{
    public function testGetEnv(): void
    {
        $this->assertEquals(expected: 'test', actual: (new Composer())->getenv(name: 'ENVIRONMENT'));
        $this->assertEquals(expected: '1', actual: (new Composer())->getenv(name: 'PHP_CS_FIXER_IGNORE_ENV', localOnly: false));
        $this->assertEquals(expected: 'TEST', actual: (new Composer())->getenv(name: 'TEST'));
    }

    /**
     * @dataProvider \Vairogs\Functions\Tests\DataProvider\ComposerDataProvider::dataProviderIsInstalled
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
