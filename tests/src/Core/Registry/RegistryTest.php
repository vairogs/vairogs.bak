<?php declare(strict_types = 1);

namespace Vairogs\Tests\Source\Core\Registry;

use InvalidArgumentException;
use Vairogs\Auth\OpenIDConnect\Configuration\DefaultProvider;
use Vairogs\Tests\Assets\VairogsTestCase;

class RegistryTest extends VairogsTestCase
{
    public function testRegistry(): void
    {
        $registry = static::getContainer()->get(id: 'vairogs.auth.openidconnect.registry');
        $this->assertGreaterThanOrEqual(expected: 1, actual: count(value: $registry->getClients()));
        $this->assertInstanceOf(expected: DefaultProvider::class, actual: $registry->getClient(name: 'vairogs'));
        $this->expectException(exception: InvalidArgumentException::class);
        $this->assertInstanceOf(expected: DefaultProvider::class, actual: $registry->getClient(name: 'vairogs999'));
    }
}
