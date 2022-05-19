<?php declare(strict_types = 1);

namespace Vairogs\Utils\Doctrine\Traits;

use DateTime;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Vairogs\Extra\Constants\Definition;

trait CreatedModified
{
    #[ORM\Column(type: Types::DATETIME_MUTABLE, options: [Definition::DEFAULT => 'CURRENT_TIMESTAMP'])]
    protected ?DateTimeInterface $creationDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, options: [Definition::DEFAULT => 'CURRENT_TIMESTAMP'])]
    protected ?DateTimeInterface $modificationDate = null;

    public function getModificationDate(): DateTimeInterface
    {
        return $this->modificationDate;
    }

    public function setModificationDate(?DateTimeInterface $modificationDate): static
    {
        $this->modificationDate = $modificationDate;

        return $this;
    }

    /** @throws Exception */
    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updatedTimestamps(): static
    {
        $this->setModificationDate(modificationDate: new DateTime());

        if (null === $this->creationDate) {
            $this->setCreationDate(creationDate: new DateTime());
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
}
