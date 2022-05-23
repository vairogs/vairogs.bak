<?php declare(strict_types = 1);

namespace Vairogs\Tests\Utils\Helper;

use DivisionByZeroError;
use Throwable;
use Vairogs\Tests\Assets\VairogsTestCase;
use Vairogs\Utils\Helper\Identification;
use ValueError;

class IdentificationTest extends VairogsTestCase
{
    /**
     * @dataProvider \Vairogs\Tests\Assets\Utils\Helper\IdentificationDataProvider::dataProviderValidatePersonCode
     */
    public function testValidatePersonCode(string $personCode, bool $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Identification())->validatePersonCode(personCode: $personCode));
    }

    public function testGetUniqueId(): void
    {
        $this->assertNotEquals(expected: (new Identification())->getUniqueId(), actual: (new Identification())->getUniqueId());

        try {
            (new Identification())->getUniqueId(length: -1);
        } catch (Throwable $exception) {
            $this->assertEquals(expected: ValueError::class, actual: $exception::class);
        }

        try {
            (new Identification())->getUniqueId(length: 0);
        } catch (Throwable $exception) {
            $this->assertEquals(expected: DivisionByZeroError::class, actual: $exception::class);
        }
    }

    public function testHash(): void
    {
        $this->assertEquals(expected: (new Identification())->getHash(text: 'vairogs'), actual: (new Identification())->getHash(text: 'vairogs'));
        $this->assertNotEquals(expected: (new Identification())->getHash(text: 'vairogs'), actual: (new Identification())->getHash(text: 'vairogs2'));
    }
}
