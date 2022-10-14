<?php declare(strict_types = 1);

namespace Vairogs\Tests\Source\Utils\Doctrine\Traits;

use DateTimeInterface;
use Vairogs\Tests\Assets\Utils\Doctrine\Traits\Entity;
use Vairogs\Tests\Assets\VairogsTestCase;

class EntityTest extends VairogsTestCase
{
    /**
     * @dataProvider \Vairogs\Tests\Assets\Utils\Doctrine\Traits\EntityDataProvider::dataProviderEntity
     *
     * @noinspection PhpUnhandledExceptionInspection
     * @noinspection UnnecessaryAssertionInspection
     */
    public function test(int $id, int $status, int $actualStatus): void
    {
        $entity = (new Entity())
            ->setId(id: $id)
            ->setStatus(status: $status)
            ->updatedTimestamps()
            ->updateStatus();

        $this->assertEquals(expected: $id, actual: $entity->getId());
        $this->assertEquals(expected: $actualStatus, actual: $entity->getStatus());
        $this->assertInstanceOf(expected: DateTimeInterface::class, actual: $entity->getCreationDate());
        $this->assertInstanceOf(expected: DateTimeInterface::class, actual: $entity->getModificationDate());
        $this->assertEquals(expected: $entity->getCreationDate()->format(format: DateTimeInterface::ATOM), actual: $entity->getModificationDate()->format(format: DateTimeInterface::ATOM));
        $this->assertIsArray(actual: $serial = $entity->jsonSerialize());
        $this->assertEquals(expected: $entity->getId(), actual: $serial['id']);
    }
}
