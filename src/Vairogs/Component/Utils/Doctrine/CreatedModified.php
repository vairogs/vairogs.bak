<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\Doctrine;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Exception;

trait CreatedModified
{
    /**
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    protected ?DateTimeInterface $creationDate = null;

    /**
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    protected ?DateTimeInterface $modificationDate = null;

    /**
     * @return DateTime
     */
    public function getModificationDate(): DateTimeInterface
    {
        return $this->modificationDate;
    }

    /**
     * @param DateTimeInterface $modificationDate
     */
    public function setModificationDate(DateTimeInterface $modificationDate): void
    {
        $this->modificationDate = $modificationDate;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * @throws Exception
     */
    public function updatedTimestamps(): void
    {
        $this->setModificationDate(new DateTime());

        if (null === $this->creationDate) {
            $this->setCreationDate(new DateTime());
        }
    }

    /**
     * @return DateTime
     */
    public function getCreationDate(): DateTimeInterface
    {
        return $this->creationDate;
    }

    /**
     * @param DateTimeInterface $creationDate
     */
    public function setCreationDate(DateTimeInterface $creationDate): void
    {
        $this->creationDate = $creationDate;
    }
}
