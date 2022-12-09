<?php declare(strict_types = 1);

namespace Vairogs\Functions\Tests\Assets\Model\Traits;

use DateTimeImmutable;
use DateTimeInterface;
use Exception;

use function get_object_vars;
use function in_array;

trait Entity
{
    protected ?DateTimeInterface $creationDate = null;
    protected ?DateTimeInterface $modificationDate = null;

    protected ?int $id = null;

    private int $status = 0;

    public function getModificationDate(): DateTimeInterface
    {
        return $this->modificationDate;
    }

    public function setModificationDate(?DateTimeInterface $modificationDate): static
    {
        $this->modificationDate = $modificationDate;

        return $this;
    }

    /**
     * @throws Exception
     */
    public function updatedTimestamps(): static
    {
        $this->setModificationDate(modificationDate: new DateTimeImmutable());

        if (null === $this->creationDate) {
            $this->setCreationDate(creationDate: new DateTimeImmutable());
        }

        return $this;
    }

    public function getCreationDate(): DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(?DateTimeInterface $creationDate): static
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return get_object_vars(object: $this);
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function updateStatus(): static
    {
        if (!in_array(needle: $this->status, haystack: [0, 1, ], strict: true)) {
            $this->status = 0;
        }

        return $this;
    }
}
