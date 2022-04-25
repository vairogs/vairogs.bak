<?php declare(strict_types = 1);

namespace Vairogs\Tests\Utils\Doctrine\Traits;

use DateTimeInterface;
use PHPUnit\Framework\TestCase;
use Vairogs\Assets\Utils\Doctrine\Traits\Entity;

class EntityTest extends TestCase
{
    /**
     * @dataProvider \Vairogs\Assets\Utils\Doctrine\Traits\EntityDataProvider::dataProviderEntity
     */
    public function test(int $id, int $status): void
    {
        $entity = (new Entity())
            ->setId(id: $id)
            ->setStatus(status: $status)
            ->updatedTimestamps();

        $this->assertEquals(expected: $id, actual: $entity->getId());
        $this->assertEquals(expected: $status, actual: $entity->getStatus());
        $this->assertInstanceOf(expected: DateTimeInterface::class, actual: $entity->getCreationDate());
        $this->assertInstanceOf(expected: DateTimeInterface::class, actual: $entity->getModificationDate());
        $this->assertEquals(expected: $entity->getCreationDate()->format(format: DateTimeInterface::ATOM), actual: $entity->getModificationDate()->format(format: DateTimeInterface::ATOM));
        $this->assertIsArray(actual: $serial = $entity->jsonSerialize());
        $this->assertEquals(expected: $entity->getId(), actual: $serial['id']);
    }
}
