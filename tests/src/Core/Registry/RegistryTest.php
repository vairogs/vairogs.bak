<?php declare(strict_types = 1);

namespace Vairogs\Tests\Core\Registry;

use InvalidArgumentException;
use Vairogs\Assets\VairogsTestCase;
use Vairogs\Auth\OpenIDConnect\Configuration\DefaultProvider;
use Vairogs\Core\Registry\Registry;

class RegistryTest extends VairogsTestCase
{
    public function testRegistry(): void
    {
        $registry = static::getContainer()->get(id: Registry::class);
        $this->assertCount(expectedCount: 1, haystack: $registry->getClients());
        $this->assertInstanceOf(expected: DefaultProvider::class, actual: $registry->getClient(name: 'vairogs'));
        $this->expectException(exception: InvalidArgumentException::class);
        $this->assertInstanceOf(expected: DefaultProvider::class, actual: $registry->getClient(name: 'vairogs2'));
    }
}
